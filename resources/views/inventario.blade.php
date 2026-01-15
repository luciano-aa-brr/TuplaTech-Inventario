<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuplaTech - Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-lavanda { background-color: #BFA2DB; }
        .text-gris-oscuro { color: #2B2B2B; }
        .bg-gris-claro { background-color: #D9D9D9; }
    </style>
</head>
<body class="bg-gris-claro min-h-screen p-2 md:p-8 font-sans">
    
    <div class="max-w-5xl mx-auto">
        <header class="bg-lavanda p-6 rounded-t-2xl shadow-lg flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gris-oscuro">TuplaTech</h1>
                <p class="text-sm text-gris-oscuro opacity-80">Inventario Inteligente</p>
            </div>
            <button onclick="abrirModal()" class="bg-white text-gris-oscuro font-bold py-2 px-4 rounded-lg shadow hover:bg-opacity-90 transition-all">
                + <span class="hidden md:inline">Nuevo Producto</span>
            </button>
        </header>

        <div class="bg-white p-4 border-b flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('productos.listar') }}" method="GET" class="w-full md:w-2/3 flex gap-2">
                <input 
                    type="text" 
                    name="buscar" 
                    value="{{ $textoBusqueda ?? '' }}"
                    placeholder="Buscar por nombre o código..." 
                    class="w-full border-2 border-gray-200 focus:border-[#BFA2DB] rounded-xl p-3 outline-none transition-all"
                >
                <button type="submit" class="bg-lavanda px-6 rounded-xl font-bold">🔍</button>
            </form>
            @if($textoBusqueda)
                <a href="{{ route('productos.listar') }}" class="text-sm font-bold text-purple-600 underline">Limpiar filtros</a>
            @endif
        </div>

        <div class="bg-white shadow-xl rounded-b-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="p-4">Producto</th>
                            <th class="p-4">Código</th>
                            <th class="p-4 text-right">Precio Venta</th>
                            <th class="p-4 text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="text-gris-oscuro">
                        @forelse($todosLosProductos as $item)
                        <tr class="border-b hover:bg-purple-50 transition-colors">
                            <td class="p-4 font-semibold">{{ $item->nombreProducto }}</td>
                            <td class="p-4 text-gray-400 font-mono text-xs">{{ $item->codigoBarras ?? 'SIN CÓDIGO' }}</td>
                            <td class="p-4 font-bold text-right text-green-600">$ {{ number_format($item->precioVenta, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->existenciasActuales < 5 ? 'bg-red-100 text-red-600' : 'bg-lavanda bg-opacity-30' }}">
                                    {{ $item->existenciasActuales }} u.
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <p class="text-gray-400 italic">No hay productos que coincidan con la búsqueda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalAgregar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-200">
            <div class="bg-lavanda p-4 font-bold text-gris-oscuro flex justify-between">
                <span>Añadir Producto</span>
                <button onclick="cerrarModal()" class="text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('productos.guardar') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nombre</label>
                    <input type="text" name="nombreProducto" required class="w-full border-b-2 border-lavanda p-2 outline-none focus:bg-purple-50">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">P. Compra</label>
                        <input type="number" name="precioCompra" required class="w-full border-b-2 border-lavanda p-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">P. Venta</label>
                        <input type="number" name="precioVenta" required class="w-full border-b-2 border-lavanda p-2 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Código</label>
                        <input type="text" name="codigoBarras" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Stock</label>
                        <input type="number" name="existencias" required class="w-full border-b-2 border-lavanda p-2 outline-none">
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-lavanda text-gris-oscuro font-bold py-3 rounded-xl shadow-md hover:scale-[1.02] transition-transform">
                        Guardar en Inventario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal() { document.getElementById('modalAgregar').classList.remove('hidden'); }
        function cerrarModal() { document.getElementById('modalAgregar').classList.add('hidden'); }
    </script>
</body>
</html>