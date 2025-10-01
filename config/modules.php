<?php

return [
    'menus' => [
        'dashboard' => [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'permission' => 'view dashboard',
        ],
        'tiendas' => [
            'label' => 'Tiendas',
            'route' => 'tiendas.index',
            'permission' => 'view tiendas',
        ],
        'vendedores' => [
            'label' => 'Vendedores',
            'route' => 'vendedores.index',
            'permission' => 'view vendedores',
        ],
        'categorias' => [
            'label' => 'Categorías',
            'route' => 'categorias.index',
            'permission' => 'view categorias',
        ],
        'productos' => [
            'label' => 'Productos',
            'route' => 'productos.index',
            'permission' => 'view productos',
        ],
        'pedidos' => [
            'label' => 'Pedidos',
            'route' => 'supervisor.pedidos.index',
            'permission' => 'view pedidos',
        ],
        'almacen_pedidos' => [
            'label' => 'Pedidos de Almacén',
            'route' => 'almacen.pedidos.index',
            'permission' => 'view pedidos almacenes',
        ],
        'cierres' => [
            'label' => 'Cierre Diario',
            'route' => 'supervisor.cierres.index',
            'permission' => 'view cierres',
        ],
        'admin_permisos' => [
            'label' => 'Permisos',
            'route' => 'admin.permissions.index',
            'permission' => 'view admin permisos',
        ],
    ],
];
