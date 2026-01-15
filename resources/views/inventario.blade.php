<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuplaTech - Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#D9D9D9] p-2 md:p-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-[#BFA2DB] p-4 md:p-6 rounded-t-xl shadow-lg">
            <h1 class="text-2xl md:text-3xl font-bold text-[#2B2B2B]">Inventario TuplaTech</h1>
            <p class="text-sm md:text-base text-[#4A444A]">Gestión de productos para pequeños negocios</p>
        </div>
        
        <div class="bg-white shadow-xl rounded-b-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 text-[#4A444A] font-bold uppercase text-xs">Producto</th>
                            <th class="p-4 text-[#4A444A] font-bold uppercase text-xs">Código</th>
                            <th class="p-4 text-[#4A444A] font-bold uppercase text-xs text-right">Precio</th>
                            <th class="p-4 text-[#4A444A] font-bold uppercase text-xs text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="text-[#2B2B2B]">
                        @foreach($todosLosProductos as $item)
                        <tr class="border-b hover:bg-purple-50 transition-colors">
                            <td class="p-4 font-medium">{{ $item->nombreProducto }}</td>
                            <td class="p-4 text-gray-500 font-mono text-xs">{{ $item->codigoBarras ?? 'S/N' }}</td>
                            <td class="p-4 font-bold text-right">$ {{ number_format($item->precioVenta, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->existenciasActuales < 10 ? 'bg-red-100 text-red-600' : 'bg-[#BFA2DB] text-[#2B2B2B]' }}">
                                    {{ $item->existenciasActuales }} u.
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="abrirModal()" class="w-full md:w-auto bg-[#BFA2DB] hover:bg-[#a889c7] text-[#2B2B2B] font-bold py-3 px-8 rounded-lg shadow-md transition-all active:scale-95">
                + Agregar Nuevo Producto
            </button>
        </div>
    </div>

    <div id="modalAgregar" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="bg-[#BFA2DB] p-4 text-[#2B2B2B] font-bold flex justify-between items-center">
                <span>Nuevo Producto</span>
                <button onclick="cerrarModal()" class="text-xl">&times;</button>
            </div>
            <form action="{{ route('productos.guardar') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#4A444A] uppercase mb-1">Nombre del Producto</label>
                    <input type="text" name="nombreProducto" class="w-full border-b-2 border-[#BFA2DB] p-2 outline-none focus:bg-purple-50 transition-colors" placeholder="Ej: Café Juan Valdez" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A444A] uppercase mb-1">P. Compra</label>
                        <input type="number" name="precioCompra" class="w-full border-b-2 border-[#BFA2DB] p-2 outline-none" placeholder="0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A444A] uppercase mb-1">P. Venta</label>
                        <input type="number" name="precioVenta" class="w-full border-b-2 border-[#BFA2DB] p-2 outline-none" placeholder="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A444A] uppercase mb-1">Cód. Barras</label>
                        <input type="text" name="codigoBarras" class="w-full border-b-2 border-[#BFA2DB] p-2 outline-none" placeholder="Opcional">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A444A] uppercase mb-1">Stock Inicial</label>
                        <input type="number" name="existencias" class="w-full border-b-2 border-[#BFA2DB] p-2 outline-none" placeholder="0" required>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 pt-4">
                    <button type="button" onclick="cerrarModal()" class="text-gray-500 font-bold py-2 order-2 md:order-1">Cancelar</button>
                    <button type="submit" class="bg-[#BFA2DB] text-[#2B2B2B] px-8 py-3 rounded-lg font-bold shadow-md hover:brightness-95 order-1 md:order-2">
                        Guardar Producto
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