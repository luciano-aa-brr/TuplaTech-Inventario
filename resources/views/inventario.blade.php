<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuplaTech - Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-lavanda { background-color: #BFA2DB; }
        .bg-gris-claro { background-color: #D9D9D9; }
        .text-gris-oscuro { color: #2B2B2B; }
    </style>
</head>
<body class="bg-gris-claro min-h-screen p-2 md:p-8 font-sans">
    <div class="max-w-6xl mx-auto">
        
        <header class="bg-lavanda p-5 md:p-6 rounded-t-2xl shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gris-oscuro">TuplaTech Inventario</h1>
                <p class="text-xs md:text-sm text-gris-oscuro opacity-80">Gestión Adaptable para tu Negocio</p>
            </div>
            <button onclick="abrirModal('modalCrear')" class="w-full md:w-auto bg-white text-gris-oscuro font-bold py-3 px-6 rounded-xl shadow hover:scale-105 transition-all active:scale-95">
                + Nuevo Producto
            </button>
        </header>

        <div class="bg-white p-4 border-b flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('productos.listar') }}" method="GET" class="w-full flex gap-2">
                <input type="text" name="buscar" value="{{ $textoBusqueda }}" placeholder="Buscar por nombre o código..." class="flex-1 border-2 border-gray-100 p-3 rounded-xl outline-none focus:border-lavanda transition-all">
                <button type="submit" class="bg-lavanda p-3 rounded-xl shadow">🔍</button>
            </form>
            @if($textoBusqueda)
                <a href="{{ route('productos.listar') }}" class="text-sm font-bold text-purple-600 underline whitespace-nowrap">Limpiar filtros</a>
            @endif
        </div>

        <div class="bg-white shadow-xl rounded-b-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="p-4">Producto</th>
                            <th class="p-4">Código</th>
                            <th class="p-4 text-right">Precio Venta</th>
                            <th class="p-4 text-center">Stock</th>
                            <th class="p-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gris-oscuro">
                        @forelse($todosLosProductos as $p)
                        <tr class="border-b hover:bg-purple-50 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-sm md:text-base">{{ $p->nombreProducto }}</div>
                                <div class="text-[10px] md:text-xs text-purple-500 uppercase font-bold">{{ $p->categoria->nombreCategoria ?? 'Sin Categoría' }}</div>
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-400">{{ $p->codigoBarras ?? 'S/N' }}</td>
                            <td class="p-4 text-right font-bold">$ {{ number_format($p->precioVenta, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $p->existenciasActuales < 5 ? 'bg-red-100 text-red-600' : 'bg-lavanda/20 text-gris-oscuro' }}">
                                    {{ $p->existenciasActuales }} u.
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <button onclick="abrirEditar({{ json_encode($p) }})" class="bg-gray-100 p-2 rounded-lg hover:bg-lavanda/30 transition-all">✏️</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center text-gray-400 italic">No se encontraron productos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCrear" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-200">
            <div class="bg-lavanda p-4 font-bold text-gris-oscuro flex justify-between items-center">
                <span>Nuevo Producto</span>
                <button onclick="cerrarModal('modalCrear')" class="text-2xl">&times;</button>
            </div>
            <form action="{{ route('productos.guardar') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="text" name="nombreProducto" placeholder="Nombre del producto" class="w-full border-b-2 border-lavanda p-2 outline-none focus:bg-purple-50" required>
                <select name="categoriaId" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    <option value="">Seleccionar Categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombreCategoria }}</option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="precioCompra" placeholder="P. Compra" class="w-full border-b-2 border-lavanda p-2 outline-none" required>
                    <input type="number" name="precioVenta" placeholder="P. Venta" class="w-full border-b-2 border-lavanda p-2 outline-none" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="codigoBarras" placeholder="Código" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="existencias" placeholder="Stock" class="w-full border-b-2 border-lavanda p-2 outline-none" required>
                </div>
                <button type="submit" class="w-full bg-lavanda text-gris-oscuro font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-all">Guardar en Inventario</button>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-200">
            <div class="bg-gris-claro p-4 font-bold text-gris-oscuro flex justify-between items-center">
                <span>Editar Producto</span>
                <button onclick="cerrarModal('modalEditar')" class="text-2xl">&times;</button>
            </div>
            <form id="formEditar" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <input type="text" name="nombreProducto" id="edit_nombre" class="w-full border-b-2 border-lavanda p-2 outline-none">
                <select name="categoriaId" id="edit_categoria" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombreCategoria }}</option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="precioCompra" id="edit_compra" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="precioVenta" id="edit_venta" class="w-full border-b-2 border-lavanda p-2 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="codigoBarras" id="edit_codigo" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="existencias" id="edit_stock" class="w-full border-b-2 border-lavanda p-2 outline-none">
                </div>
                <button type="submit" class="w-full bg-lavanda text-gris-oscuro font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-all">Actualizar Producto</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }

        function abrirEditar(p) {
            document.getElementById('formEditar').action = "/productos/actualizar/" + p.id;
            document.getElementById('edit_nombre').value = p.nombreProducto;
            document.getElementById('edit_categoria').value = p.categoriaId;
            document.getElementById('edit_compra').value = p.precioCompra;
            document.getElementById('edit_venta').value = p.precioVenta;
            document.getElementById('edit_codigo').value = p.codigoBarras;
            document.getElementById('edit_stock').value = p.existenciasActuales;
            abrirModal('modalEditar');
        }
    </script>
</body>
</html>