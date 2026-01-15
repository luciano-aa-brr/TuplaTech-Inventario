<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuplaTech - Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> .bg-lavanda { background-color: #BFA2DB; } </style>
</head>
<body class="bg-[#D9D9D9] min-h-screen p-2 md:p-8 font-sans text-[#2B2B2B]">
    <div class="max-w-6xl mx-auto">
        
        <header class="bg-lavanda p-5 rounded-t-2xl shadow-lg flex justify-between items-center">
            <h1 class="text-2xl font-bold">TuplaTech Shop</h1>
            <button onclick="abrirModal('modalCrear')" class="bg-white px-4 py-2 rounded-xl font-bold shadow hover:scale-105 transition-all">+ Registrar Producto</button>
        </header>

        @if(session('exito'))
            <div id="alerta" class="mt-4 p-4 bg-white border-l-8 border-[#BFA2DB] rounded-xl shadow-md flex justify-between items-center animate-bounce">
                <span class="font-bold">✨ {{ session('exito') }}</span>
                <button onclick="this.parentElement.remove()" class="text-xl">&times;</button>
            </div>
            <script>setTimeout(() => { document.getElementById('alerta')?.remove(); }, 3000);</script>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-[#BFA2DB]">
                <p class="text-xs font-bold text-gray-400 uppercase">Inversión Total</p>
                <p class="text-2xl font-bold">$ {{ number_format($inversionTotal, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-green-400">
                <p class="text-xs font-bold text-gray-400 uppercase">Ganancia Proyectada</p>
                <p class="text-2xl font-bold text-green-600">$ {{ number_format($gananciaProyectada, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-red-400">
                <p class="text-xs font-bold text-gray-400 uppercase">Stock Crítico</p>
                <p class="text-2xl font-bold text-red-500">{{ $alertasStock }} productos</p>
            </div>
        </div>

        <div class="bg-white p-4 border-b flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('productos.listar') }}" method="GET" class="w-full flex gap-2">
                <input type="text" name="buscar" value="{{ $textoBusqueda }}" placeholder="Buscar por nombre o código..." class="w-full border-2 border-gray-100 p-2 rounded-xl outline-none focus:border-[#BFA2DB]">
                <button type="submit" class="bg-lavanda p-2 rounded-xl px-4">🔍</button>
            </form>
            @if($textoBusqueda)
                <a href="{{ route('productos.listar') }}" class="text-sm font-bold text-purple-600 underline whitespace-nowrap">Limpiar búsqueda</a>
            @endif
        </div>

        <div class="bg-white shadow-xl rounded-b-2xl overflow-x-auto">
            <table class="w-full text-left min-w-[750px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                        <th class="p-4">Producto / Categoría</th>
                        <th class="p-4">Código</th>
                        <th class="p-4 text-right">Precio Venta</th>
                        <th class="p-4 text-center">Stock</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todosLosProductos as $p)
                    <tr class="border-b hover:bg-purple-50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold">{{ $p->nombreProducto }}</div>
                            <div class="text-[10px] text-purple-500 font-bold uppercase">{{ $p->categoria->nombreCategoria ?? 'General' }}</div>
                        </td>
                        <td class="p-4 font-mono text-xs text-gray-400">{{ $p->codigoBarras ?? 'S/N' }}</td>
                        <td class="p-4 text-right font-bold text-green-600">$ {{ number_format($p->precioVenta, 0, ',', '.') }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $p->existenciasActuales < 5 ? 'bg-red-100 text-red-600' : 'bg-lavanda/20' }}">
                                {{ $p->existenciasActuales }} u.
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-3">
                                <button onclick="abrirEditar({{ json_encode($p) }})" class="hover:scale-125 transition-transform">✏️</button>
                                <button onclick="confirmarEliminar({{ $p->id }}, '{{ $p->nombreProducto }}')" class="hover:scale-125 transition-transform">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalCrear" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="bg-lavanda p-4 font-bold flex justify-between">
                <span>Registrar Producto</span>
                <button onclick="cerrarModal('modalCrear')">&times;</button>
            </div>
            <form action="{{ route('productos.guardar') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="text" name="nombreProducto" placeholder="Nombre" required class="w-full border-b-2 border-lavanda p-2 outline-none focus:bg-purple-50">
                <div class="flex gap-2">
                    <select name="categoriaId" class="flex-1 border-b-2 border-lavanda p-2 outline-none">
                        <option value="">Seleccionar Categoría</option>
                        @foreach($categorias as $cat) <option value="{{ $cat->id }}">{{ $cat->nombreCategoria }}</option> @endforeach
                    </select>
                    <button type="button" onclick="abrirModal('modalCategoria')" class="bg-lavanda px-3 rounded-lg font-bold" title="Nueva Categoría">+</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="precioCompra" placeholder="P. Compra" required class="border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="precioVenta" placeholder="P. Venta" required class="border-b-2 border-lavanda p-2 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="codigoBarras" placeholder="Código Barras" class="border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="existencias" placeholder="Stock Inicial" required class="border-b-2 border-lavanda p-2 outline-none">
                </div>
                <button type="submit" class="w-full bg-lavanda font-bold py-3 rounded-xl shadow-lg mt-4 active:scale-95 transition-all">Guardar Producto</button>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden">
            <div class="bg-lavanda p-4 font-bold flex justify-between">
                <span>Editar Producto</span>
                <button onclick="cerrarModal('modalEditar')">&times;</button>
            </div>
            <form id="formEditar" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <input type="text" name="nombreProducto" id="edit_nombre" class="w-full border-b-2 border-lavanda p-2 outline-none">
                <select name="categoriaId" id="edit_categoria" class="w-full border-b-2 border-lavanda p-2 outline-none">
                    @foreach($categorias as $cat) <option value="{{ $cat->id }}">{{ $cat->nombreCategoria }}</option> @endforeach
                </select>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="precioCompra" id="edit_compra" class="border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="precioVenta" id="edit_venta" class="border-b-2 border-lavanda p-2 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="codigoBarras" id="edit_codigo" class="border-b-2 border-lavanda p-2 outline-none">
                    <input type="number" name="existencias" id="edit_stock" class="border-b-2 border-lavanda p-2 outline-none">
                </div>
                <button type="submit" class="w-full bg-lavanda font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-all">Actualizar Producto</button>
            </form>
        </div>
    </div>

    <div id="modalCategoria" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-[70]">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl">
            <h3 class="font-bold mb-4">Nueva Categoría de Inventario</h3>
            <form action="{{ route('categorias.guardar') }}" method="POST">
                @csrf
                <input type="text" name="nombreCategoria" required placeholder="Nombre (Ej: Periféricos)" class="w-full border-b-2 border-lavanda p-2 outline-none mb-6">
                <button type="submit" class="w-full bg-lavanda font-bold py-3 rounded-xl shadow-lg">Crear Categoría</button>
            </form>
            <button onclick="cerrarModal('modalCategoria')" class="w-full text-xs mt-4 text-gray-400">Cerrar ventana</button>
        </div>
    </div>

    <div id="modalEliminar" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-[60]">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 text-center shadow-2xl">
            <h3 class="text-xl font-bold mb-2 text-red-500">¿Eliminar Producto?</h3>
            <p class="text-sm text-gray-500 mb-6">Confirmas que quieres borrar: <span id="nombreEliminar" class="font-bold text-black"></span></p>
            <form id="formEliminar" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl mb-2 hover:bg-red-600 transition-colors">Sí, borrar</button>
                <button type="button" onclick="cerrarModal('modalEliminar')" class="w-full bg-gray-100 py-3 rounded-xl font-bold">Cancelar</button>
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
            document.getElementById('edit_codigo').value = p.codigoBarras || '';
            document.getElementById('edit_stock').value = p.existenciasActuales;
            abrirModal('modalEditar');
        }

        function confirmarEliminar(id, nombre) {
            document.getElementById('nombreEliminar').innerText = nombre;
            document.getElementById('formEliminar').action = "/productos/eliminar/" + id;
            abrirModal('modalEliminar');
        }
    </script>
</body>
</html>