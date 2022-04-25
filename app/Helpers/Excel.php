<?php
namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use App\Models\TimingLogs;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Excel
{
    protected static $arrayData = array();
    protected static $arrayHeaders = array();
    protected static $arrayBody = array();

    /**
     * @var array<mixed> Header styles for excel export
     */
    protected static $headerStyleArray = [
        'font' => [
            'bold' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['argb' => '00000000'],
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => [
                'argb' => '808080',
            ],
        ]
    ];

    /**
     * @var array<mixed> Data styles for excel export
     */
    protected static $bodyStyleArray = [
        'font' => [
            'bold' => false,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => '00000000'],
            ],
        ]
    ];

    /**
     * Exports xls file.
     *
     * @return writer|bool
     */
    public static function exportTimingsByUserId(int $user_id){

        $userInfo = User::find($user_id);

        $logs = TimingLogs::getList((int) $user_id);
        $logsHeaders = $logs['headers'];
        $logsBody = $logs['body'];

        self::setArrayData($logsHeaders, $logsBody);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()
            ->fromArray(
                self::$arrayData,
                NULL,
                'A1'
            );
        
        $sheet->getDefaultColumnDimension()->setWidth(150, 'pt');

        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex((count($logsHeaders)+1)) . '1' )
              ->applyFromArray(self::$headerStyleArray);

        $sheet->getStyle('A1:' . (string)$sheet->getCoordinates()[array_key_last($sheet->getCoordinates())])
              ->applyFromArray(self::$bodyStyleArray);

        $fileName = $userInfo->surname . "_" . $userInfo->name . ".xlsx";

        self::returnWriter($spreadsheet, $fileName);
    }

    /**
     * Sets value of local static property $arrayData.
     *
     * @param array $headerParam
     * @param array $bodyParam
     * @return void
     */
    protected static function setArrayData(array $headerParam, array $bodyParam){
        array_unshift($headerParam, [
            'key' => 'No',
            'value' => 'No'
        ]);
        for ($i=0; $i < count($bodyParam); $i++) { 
            $bodyParam[$i]['No'] = $i+1;
        }
        foreach($headerParam as $key=>$header){
            self::$arrayHeaders[$key] = $header['value'];
            foreach($bodyParam as $bkey => $body){
                self::$arrayBody[$bkey][] = $body[$header['key']];
            }
        }
        self::$arrayData[] = self::$arrayHeaders;
        foreach(self::$arrayBody as $body){
            self::$arrayData[] = $body;
        }
    }

    /**
     * Return writer.
     *
     * @param Spreadsheet $spreadsheet
     * @param string $fileName
     * @return writer
     */
    protected function returnWriter(Spreadsheet $spreadsheet, string $fileName){
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        return $writer->save('php://output');
    }

}
