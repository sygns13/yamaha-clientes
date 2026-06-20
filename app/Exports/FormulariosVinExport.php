<?php

namespace App\Exports;

use App\Models\Motocicleta;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormulariosVinExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    private int $currentRow = 2;
    private array $vinImagenes = [];

    public function __construct(private array $filtros = []) {}

    public function query()
    {
        return Motocicleta::with(['cliente', 'modeloMoto'])
            ->when($this->filtros['tipo'] ?? null, fn ($q, $v) => $q->where('tipo_formulario', $v))
            ->when($this->filtros['nombres'] ?? null, fn ($q, $v) => $q->whereHas('cliente', fn ($q2) => $q2->where('nombres_apellidos', 'like', "%$v%")))
            ->when($this->filtros['celular'] ?? null, fn ($q, $v) => $q->whereHas('cliente', fn ($q2) => $q2->where('celular', 'like', "%$v%")))
            ->when($this->filtros['modelo'] ?? null, function ($q, $v) {
                $v === 'otros'
                    ? $q->whereNull('modelo_moto_id')->whereNotNull('modelo_moto_otro')
                    : $q->where('modelo_moto_id', $v);
            })
            ->when($this->filtros['vin'] ?? null, fn ($q, $v) => $q->where('vin_descripcion', 'like', "%$v%"))
            ->latest();
    }

    public function headings(): array
    {
        return ['Nombres y Apellidos', 'Celular', 'Correo', 'Modelo de Moto', 'Tipo VIN', 'Código / Imagen VIN', 'Fecha de Registro'];
    }

    public function map($m): array
    {
        // Registrar la ruta absoluta de la imagen para los registros VIN-2
        if ($m->tipo_formulario === 'VIN2' && $m->vin_imagen) {
            $path = Storage::disk('public')->path($m->vin_imagen);
            if (file_exists($path)) {
                $this->vinImagenes[$this->currentRow] = $path;
            }
        }
        $this->currentRow++;

        return [
            $m->cliente->nombres_apellidos,
            $m->cliente->celular,
            $m->cliente->correo ?? '-',
            $m->modeloMoto ? $m->modeloMoto->nombre : 'OTROS: ' . $m->modelo_moto_otro,
            $m->tipo_formulario,
            // VIN-1: texto del código | VIN-2: vacío, la imagen se inserta vía AfterSheet
            $m->tipo_formulario === 'VIN1' ? $m->vin_descripcion : '',
            $m->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '003087']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Columna F: ancho fijo para alojar las imágenes (ShouldAutoSize la dejaría muy angosta)
                $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(22);

                foreach ($this->vinImagenes as $row => $path) {
                    try {
                        $drawing = new Drawing();
                        $drawing->setName('VIN');
                        $drawing->setDescription('Imagen VIN');
                        $drawing->setPath($path);
                        $drawing->setCoordinates('F' . $row);
                        $drawing->setOffsetX(2);
                        $drawing->setOffsetY(2);
                        $drawing->setResizeProportional(true);
                        $drawing->setHeight(80);  // px; el ancho se calcula proporcionalmente
                        $drawing->setWorksheet($sheet);

                        // Alto de fila ajustado para mostrar la imagen (80 px ≈ 60 pt, +3 pt de margen)
                        $sheet->getRowDimension($row)->setRowHeight(63);
                    } catch (\Throwable) {
                        // Imagen corrupta o formato no soportado: se omite sin detener la exportación
                    }
                }
            },
        ];
    }
}
