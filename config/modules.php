<?php

return [
    'menus' => [
        'dashboard' => [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'permissions' => [
                'view' => 'view dashboard',
            ],
        ],
        'tiendas' => [
            'label' => 'Tiendas',
            'route' => 'tiendas.index',
            'permissions' => [
                'view' => 'view tiendas',
                'manage' => 'manage tiendas',
            ],
        ],
        'vendedores' => [
            'label' => 'Vendedores',
            'route' => 'vendedores.index',
            'permissions' => [
                'view' => 'view vendedores',
                'manage' => 'manage vendedores',
            ],
        ],
        'categorias' => [
            'label' => 'Categorías',
            'route' => 'categorias.index',
            'permissions' => [
                'view' => 'view categorias',
                'manage' => 'manage categorias',
            ],
        ],
        'productos' => [
            'label' => 'Productos',
            'route' => 'productos.index',
            'permissions' => [
                'view' => 'view productos',
                'manage' => 'manage productos',
            ],
        ],
        'pedidos' => [
            'label' => 'Pedidos',
            'route' => 'supervisor.pedidos.index',
            'permissions' => [
                'view' => 'view pedidos',
                'manage' => 'manage pedidos',
            ],
        ],
        'almacen_pedidos' => [
            'label' => 'Pedidos de Almacén',
            'route' => 'almacen.pedidos.index',
            'permissions' => [
                'view' => 'view pedidos almacenes',
            ],
        ],
        'cierres' => [
            'label' => 'Cierre Diario',
            'route' => 'supervisor.cierres.index',
            'permissions' => [
                'view' => 'view cierres',
            ],
        ],
        'admin_permisos' => [
            'label' => 'Permisos',
            'route' => 'admin.permissions.index',
            'permissions' => [
                'view' => 'view admin permisos',
            ],
        ],
        'admin_roles' => [
            'label' => 'Roles',
            'route' => 'admin.roles.permissions.index',
            'permissions' => [
                'view' => 'view admin roles',
                'manage' => 'manage admin roles',
            ],
        ],
        'admin_usuarios' => [
            'label' => 'Usuarios',
            'route' => 'admin.users.index',
            'permissions' => [
                'view' => 'view admin users',
                'manage' => 'manage admin users',
            ],
        ],
    ],
];
