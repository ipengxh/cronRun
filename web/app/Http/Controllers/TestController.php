<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller
{
    public function index()
    {
        $sheets = [
            ['ID', 'test'],
            [1, 'A'],
            [2, '你好，世界']
        ];
        return $this->csv($sheets, 'test');
    }

    public function csv($sheet, $title)
    {
        // 设置 header，不然的话就会把csv直接显示在网页上
        header('Content-Type: application/CSV; charset=utf-8');
        header("Content-disposition: attachment; filename={$title}.csv");
        $out = fopen('php://output', 'w');
        // 设置 BOM 头，在 UTF-8 编码下，若不加 BOM 头
        // 会被 Microsoft Office Excel 以 GBK 编码方式加载
        // 在这里强行设置 BOM 头
        //fputs($out, chr(239).chr(187).chr(191));
        foreach ($sheet as $id => $line) {
            fputcsv($out, $line);
        }
        fclose($out);
    }
}
