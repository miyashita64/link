<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Diarie;
use App\DiarieItem;
use App\SignImage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Excel extends Model
{
    /**
     * Excelファイル生成
     */
    public static function downloadServiceReportFile($datas){
        $ym = explode("-", $datas["date"]);

        if(!$datas["client"] || !$datas["facilitie"]) exit();
        if(count($ym)<2) exit();

        $diaries = Diarie::where("client_id", $datas["client"]["id"])->where("date", ">=", $ym[0]."-".$ym[1])->where("date", "<=", $ym[0]."-".substr("0".($ym[1]+1),-2))->orderBy('date','asc')->get();

        // スプレッドシート作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');

        $sheet->setCellValueByColumnAndRow(2, 2, '利用者氏名');
        $sheet->setCellValueByColumnAndRow(4, 2, $datas["client"]["name"]);
        $sheet = self::write_border($sheet, 2, 2, 5, 2, [0,0,1,0]);

        foreach($diaries as $count => $diarie){
            $row = floor($count/2);
            $col = $count%2;

            $itemKeys = [
                "defication" => "排泄",
                "hydration"  => "水分補給",
                "medication" => "服薬",
            ];
            foreach($itemKeys as $key => $val){
                $dItems = DiarieItem::where("diarie_id", $diarie["id"])->where("activity", $val)->orderBy('time','asc')->get();
                $text = "";
                foreach($dItems as $dItem){
                    $text .= substr($dItem["time"], -8, 5).",";
                }
                $diarie[$key] = $text;
            }

            $sheet->getColumnDimension('B')->setWidth(15.57);
            $sheet->getColumnDimension('M')->setWidth(15.57);
            $sheet = self::write_border($sheet, 2+11*$col, 4+16*$row, 2+11*$col, 4+16*$row, [0,0,0,1]);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  4+16*$row, 'サービス提供日：');
            $sheet->setCellValueByColumnAndRow( 3+11*$col,  4+16*$row, $diarie["date"]);
            if($diarie["service_type"]==0){
                $sheet = self::write_border($sheet, 2+11*$col, 4+16*$row, 6+11*$col, 4+16*$row, [1,0,1,0]);
                $sheet->setCellValueByColumnAndRow( 6+11*$col,  4+16*$row, "キャンセル");
                $sheet = self::write_border($sheet, 6+11*$col, 4+16*$row, 6+11*$col, 4+16*$row, [0,1,0,0]);
                continue;
            }
            $sheet->setCellValueByColumnAndRow( 6+11*$col,  4+16*$row, 'サービス提供時間：');
            $sheet->setCellValueByColumnAndRow( 8+11*$col,  4+16*$row, substr($diarie["pick_depart_time"], 0, -3).'~'.substr($diarie["drop_arrive_time"], 0, -3));
            $sheet = self::write_border($sheet, 2+11*$col, 4+16*$row, 11+11*$col, 4+16*$row, [1,0,1,0]);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  5+16*$row, '送迎記録');
            if(isset($diarie["pick_driver"]["name"])) $sheet->setCellValueByColumnAndRow( 3+11*$col,  5+16*$row, '担当：'.$diarie["pick_driver"]["name"]);
            $sheet->setCellValueByColumnAndRow( 3+11*$col,  5+16*$row, '提供形態');
            $sheet->setCellValueByColumnAndRow( 4+11*$col,  5+16*$row, $diarie["service_type"]);
            $sheet->setCellValueByColumnAndRow( 6+11*$col,  5+16*$row, '迎え：');
            if(isset($diarie["pick_arrive_time"])) $sheet->setCellValueByColumnAndRow( 7+11*$col,  5+16*$row, substr($diarie["pick_arrive_time"], 0, -3).'~'.substr($diarie["pick_depart_time"], 0, -3));
            $sheet->setCellValueByColumnAndRow( 9+11*$col,  5+16*$row, '送り：');
            if(isset($diarie["drop_arrive_time"])) $sheet->setCellValueByColumnAndRow(10+11*$col,  5+16*$row, substr($diarie["drop_arrive_time"], 0, -3).'~'.substr($diarie["drop_depart_time"], 0, -3));
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  6+16*$row, '今日の活動');
            $sheet->setCellValueByColumnAndRow( 3+11*$col,  6+16*$row, '午前：');
            $sheet->setCellValueByColumnAndRow( 6+11*$col,  6+16*$row, '午後：');
            $sheet->setCellValueByColumnAndRow( 9+11*$col,  6+16*$row, '個別：');
            $sheet = self::write_border($sheet, 2+11*$col, 6+16*$row, 11+11*$col, 6+16*$row, [1,0,1,0]);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  7+16*$row, '排尿・排便');
            $sheet->mergeCellsByColumnAndRow(2+11*$col, 8+16*$row, 2+11*$col, 10+16*$row);
            $sheet->getStyleByColumnAndRow( 2+11*$col,  8+16*$row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(2+11*$col,  8+16*$row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  8+16*$row, $diarie["defication"]);
            $sheet->setCellValueByColumnAndRow( 2+11*$col, 11+16*$row, '水分摂取');
            $sheet->mergeCellsByColumnAndRow(2+11*$col, 12+16*$row, 2+11*$col, 14+16*$row);
            $sheet->getStyleByColumnAndRow( 2+11*$col,  12+16*$row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(2+11*$col,  12+16*$row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  12+16*$row, $diarie["hydration"]);
            $sheet->setCellValueByColumnAndRow( 2+11*$col, 15+16*$row, '服薬');
            $sheet->mergeCellsByColumnAndRow(2+11*$col, 16+16*$row, 2+11*$col, 18+16*$row);
            $sheet->getStyleByColumnAndRow( 2+11*$col,  16+16*$row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(2+11*$col,  16+16*$row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setCellValueByColumnAndRow( 2+11*$col,  16+16*$row, $diarie["medication"]);
            $sheet = self::write_border($sheet, 2+11*$col, 5+16*$row, 2+11*$col, 18+16*$row, [0,1,0,1]);
            $sheet->setCellValueByColumnAndRow( 3+11*$col,  7+16*$row, '支援内容');
            if(isset($diarie["writer"]["name"])) $sheet->setCellValueByColumnAndRow( 9+11*$col,  7+16*$row, '担当：'.$diarie["writer"]["name"]);
            $sheet = self::write_border($sheet, 3+11*$col, 7+16*$row, 11+11*$col, 7+16*$row, [0,0,1,0]);
            $sheet->mergeCellsByColumnAndRow(3+11*$col, 8+16*$row, 11+11*$col, 18+16*$row);
            $sheet->getStyleByColumnAndRow( 3+11*$col,  8+16*$row)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(3+11*$col,  8+16*$row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setCellValueByColumnAndRow( 3+11*$col,  8+16*$row, $diarie["content"]);
            $sheet = self::write_border($sheet, 2+11*$col, 18+16*$row, 11+11*$col, 18+16*$row, [0,0,1,0]);
            $sheet = self::write_border($sheet,11+11*$col, 4+16*$row, 11+11*$col, 18+16*$row, [0,1,0,0]);
        }

        // Excelファイル書き出し
        $writer = new Xlsx($spreadsheet);
        $fileName = $datas["date"]."_".$datas["client"]["name"]."_".$datas["client"]["id"]."_".$datas["facilitie"]["name"]."_".$datas["facilitie"]["id"].'.xlsx';
        $fileName = str_replace(array(" ", "　"), "_", $fileName);
        $filePath = app_path("app_storage/service_reports/".$fileName);
        $outputName = "サービス提供記録_".$datas["date"]."_".$datas["client"]["id"]."_".$datas["client"]["name"].".xlsx";
        $outputName = str_replace(array(" ", "　"), "_", $outputName);
        $writer->save($filePath);

        return array($filePath, $outputName);
    }

    /**
     * 送迎記録Excelファイル生成
     */
    public static function downloadTransferReportFile($datas){
        $ym = explode("-", $datas["date"]);

        if(!$datas["client"] || !$datas["facilitie"]) exit();
        if(count($ym)<2) exit();

        $diaries = Diarie::where("client_id", $datas["client"]["id"])->where("date", ">=", $ym[0]."-".$ym[1])->where("date", "<=", $ym[0]."-".substr("0".($ym[1]+1),-2))->orderBy("date", "asc")->get();

        // スプレッドシート作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');

        $dcells = [
            //[col, row, col2, row2, value, border],
            [2,2,3,2,$ym[0]."年".$ym[1]."月分",[]],
            [6,2,11,2,"放課後等デイサービス提供実績記録票",[]],
            [2,3,3,4,"受給者証番号",[1,1,1,1]],
            [4,3,4,4,$datas["client"]["benefic_num"],[1,1,1,1]],
            [5,3,6,3,"給付決定保護者氏名",[1,1,0,1]],
            [5,4,6,4,"（障がい児童名）",[]],
            [7,3,9,4,$datas["client"]["name"],[1,1,1,1]],
            [10,3,11,4,"事業所番号",[1,1,1,1]],
            [12,3,13,4,"",[1,1,1,1]],
            [2,5,3,5,"契約支給量",[1,1,1,1]],
            [4,5,9,5,"",[1,1,1,1]],
            [10,5,11,5,"事業者及びその事業所",[1,1,1,1]],
            [12,5,13,5,$datas["facilitie"]["name"],[1,1,1,1]],
            [2,7,2,9,"日付",[1,1,1,1]],
            [3,7,3,9,"曜日",[1,1,1,1]],
            [4,7,11,7,"サービス提供実績",[1,1,1,1]],
            [12,7,12,9,"保護者等確認印",[1,1,1,1]],
            [13,7,13,9,"備考",[1,1,1,1]],
            [4,8,4,9,"サービス提供の状況",[1,1,1,1]],
            [5,8,5,9,"提供形態",[1,1,1,1]],
            [6,8,6,9,"開始時間",[1,1,1,1]],
            [7,8,7,9,"終了時間",[1,1,1,1]],
            [8,8,9,8,"送迎加算",[1,1,1,1]],
            [8,9,8,9,"往",[1,1,1,1]],
            [9,9,9,9,"複",[1,1,1,1]],
            [10,8,10,8,"家庭連携加算",[1,1,1,1]],
            [10,9,10,9,"時間数",[1,1,1,1]],
            [11,8,11,8,"訪問支援特別加算",[1,1,1,1]],
            [11,9,11,9,"時間数",[1,1,1,1]]
        ];
        foreach($dcells as $cell){
            $sheet->mergeCellsByColumnAndRow($cell[0], $cell[1], $cell[2], $cell[3]);
            $sheet->getStyleByColumnAndRow($cell[0], $cell[1])->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow($cell[0], $cell[1])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setCellValueByColumnAndRow($cell[0], $cell[1], $cell[4]);
            if(count($cell[5])==4) $sheet = self::write_border($sheet, $cell[0], $cell[1], $cell[2], $cell[3], $cell[5]);
        }

        $week = ['日', '月', '火', '水', '木', '金', '土'];
        foreach($diaries as $count => $diarie){
            $row = $count+10;
            $sheet->getColumnDimension('L')->setWidth(14.25);
            $sheet->getRowDimension((string)$row)->setRowHeight(30);

            $date = explode("-", $diarie["date"]);
            $day = new DateTime($diarie["date"]);
            $d = $date[2];
            if($d == null){
                $d = "0";
            } else if(substr($d, 0, 1) == "0"){
                $d = substr($d, 1);
            } 
            
            $d_cells = [
                "date"  => [2 , $d],
                "day"   => [3 , $week[$day->format('w')]],
                "b1"    => [4 , ""],
                "type"  => [5 , $diarie["service_type"]],
                "start" => [6 , $diarie["pick_arrive_time"] ?? $diarie["in_time"]],
                "end"   => [7 , $diarie["drop_depart_time"] ?? $diarie["out_time"]],
                "pick"  => [8 , ($diarie["pick_driver_id"]!=-1)? "1" : ""],
                "drop"  => [9 , ($diarie["drop_driver_id"]!=-1)? "1" : ""],
                "b2"    => [10, ""],
                "b3"    => [11, ""],
                "sign"  => [12, ""],
                "b4"    => [13, ""],
            ];

            foreach($d_cells as $cell){
                $sheet->getStyleByColumnAndRow($cell[0], $row)->getAlignment()->setWrapText(true);
                $sheet->getStyleByColumnAndRow($cell[0], $row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $sheet->setCellValueByColumnAndRow($cell[0], $row, $cell[1]);
                $sheet = self::write_border($sheet, $cell[0], $row, $cell[0], $row, [1,1,1,1]);
            }

            $signImg = SignImage::find($diarie["sign_id"]);
            if(isset($signImg["path"])){
                $path = explode("../",$signImg["path"]);
                //dd($path);
                $drawing = new Drawing();
                $drawing->setName('Sign');
                $drawing->setDescription('Sign');
                $drawing->setPath(public_path($path[1]));
                $drawing->setResizeProportional(true);
                $drawing->setWidth(60);
                $drawing->setHeight(30);
                $drawing->setCoordinates('L'.(string)$row);
                $drawing->setWorksheet($sheet);
            }
        }

        // Excelファイル書き出し
        $writer = new Xlsx($spreadsheet);
        $fileName = $datas["date"]."_".$datas["client"]["name"]."_".$datas["client"]["id"]."_".$datas["facilitie"]["name"]."_".$datas["facilitie"]["id"].'.xlsx';
        $fileName = str_replace(array(" ", "　"), "_", $fileName);
        $filePath = app_path("app_storage/transfer_reports/".$fileName);
        $outputName = "提供実績記録票_".$datas["date"]."_".$datas["client"]["id"]."_".$datas["client"]["name"].".xlsx";
        $outputName = str_replace(array(" ", "　"), "_", $outputName);
        $writer->save($filePath);

        return array($filePath, $outputName);
    }

    /**
     * Excelファイル書き出し補助（枠線）
     * @param $sheet
     * @param $s_col
     * @param $s_row
     * @param $e_col
     * @param $e_row
     * @param $type
     */
    private static function write_border($sheet,$s_col,$s_row,$e_col,$e_row,$type){
        for($i=$s_col; $i<=$e_col; $i++){
            for($j=$s_row; $j<=$e_row; $j++){
                $borders = $sheet->getStyleByColumnAndRow($i, $j)->getBorders();
                if($type[0]) $borders->getTop()->setBorderStyle('thin');
                if($type[1]) $borders->getRight()->setBorderStyle('thin');
                if($type[2]) $borders->getBottom()->setBorderStyle('thin');
                if($type[3]) $borders->getLeft()->setBorderStyle('thin');
            }
        }
        return $sheet;
    }
}
