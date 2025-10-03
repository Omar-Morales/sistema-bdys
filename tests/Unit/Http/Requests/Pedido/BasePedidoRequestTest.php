<?php

namespace Tests\Unit\Http\Requests\Pedido;

use App\Http\Requests\Pedido\StorePedidoRequest;
use App\Http\Requests\Pedido\UpdatePedidoRequest;
use App\Models\Pedido;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\PresenceVerifierInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BasePedidoRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Validator::setPresenceVerifier(new class implements PresenceVerifierInterface {
            public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
            {
                return 1;
            }

            public function getMultiCount($collection, $column, array $values, array $extra = [])
            {
                return count($values);
            }
        });
    }

    #[DataProvider('pedidoRequestsProvider')]
    public function testValidatedCalculatesChangeWhenPaymentExceedsTotal(string $requestClass): void
    {
        $request = new $requestClass();
        $request->setContainer($this->app);

        $request->replace([
            'tienda_id' => 1,
            'vendedor_id' => 2,
            'almacen_id' => 3,
            'encargado_id' => 4,
            'monto_total' => 100,
            'monto_pagado' => 150,
            'metraje_total' => 10,
            'cantidad_total' => 5,
            'unidad_referencia' => 'unidad',
            'precio_promedio' => 20,
            'tipo_entrega' => Pedido::TIPO_ENTREGA_RECOJO,
            'tipo_pago' => Pedido::TIPO_PAGO_EFECTIVO,
            'estado_pedido' => Pedido::ESTADO_PEDIDO_PENDIENTE,
            'notas' => 'Entrega inmediata',
            'cobra_almacen' => '1',
        ]);

        $this->callPrepareForValidation($request);

        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->passes());

        $request->setValidator($validator);

        $data = $request->validated();

        $this->assertSame(150.0, $data['monto_pagado']);
        $this->assertSame(0.0, $data['saldo_pendiente']);
        $this->assertSame(Pedido::ESTADO_PAGO_VUELTO, $data['estado_pago']);
        $this->assertTrue($data['cobra_almacen']);
        $this->assertSame(50.0, $request->cambioCalculado());
    }

    #[DataProvider('pedidoRequestsProvider')]
    public function testValidatedHandlesPendingEstadoPagoWhenNoPayment(string $requestClass): void
    {
        $request = new $requestClass();
        $request->setContainer($this->app);

        $request->replace([
            'tienda_id' => 1,
            'vendedor_id' => 2,
            'almacen_id' => 3,
            'encargado_id' => 4,
            'monto_total' => 80,
            'metraje_total' => 10,
            'cantidad_total' => 5,
            'unidad_referencia' => 'unidad',
            'precio_promedio' => 20,
            'tipo_entrega' => Pedido::TIPO_ENTREGA_RECOJO,
            'tipo_pago' => Pedido::TIPO_PAGO_EFECTIVO,
            'estado_pedido' => Pedido::ESTADO_PEDIDO_PENDIENTE,
            'notas' => 'Sin pago aún',
            'cobra_almacen' => '0',
        ]);

        $this->callPrepareForValidation($request);

        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->passes());

        $request->setValidator($validator);

        $data = $request->validated();

        $this->assertSame(0.0, $data['monto_pagado']);
        $this->assertSame(80.0, $data['saldo_pendiente']);
        $this->assertSame(Pedido::ESTADO_PAGO_PENDIENTE, $data['estado_pago']);
        $this->assertFalse($data['cobra_almacen']);
        $this->assertSame(0.0, $request->cambioCalculado());
    }

    public static function pedidoRequestsProvider(): array
    {
        return [
            [StorePedidoRequest::class],
            [UpdatePedidoRequest::class],
        ];
    }

    private function callPrepareForValidation(object $request): void
    {
        $method = (new \ReflectionClass($request))->getMethod('prepareForValidation');
        $method->setAccessible(true);
        $method->invoke($request);
    }
}
