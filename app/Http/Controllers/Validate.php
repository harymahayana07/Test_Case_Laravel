<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Validate_me;
use Illuminate\Support\Facades\Validator;

class Validate extends Controller
{

    // create data
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            // validasi range tahun lahir yang diperbolehkan dari 1900 - 1999
            'year' => array('required','numeric','regex:#^(19[0-9][0-9]|1999)$#')
        ]);

        $year_v = ($request->input('year'));
        // validasi tahun lahir 3 digit terakhir harus genap
        if($year_v[2] % 2 == 1){
            $validasi->fails();
            return response()->json([
                'pesan' => 'value salah, gagal diproses!ada error'
            ], 404);
        }
        // validasi tahun lahir 4 digit terakhir harus ganjil
        if ($year_v[3] % 2 == 0) {
            $validasi->fails();
            return response()->json([
                'pesan' => 'value salah, gagal diproses!ada error'
            ], 404);
        }
        
        // 2. Proses input
        $my_name = $request->input('name');
        $my_year = $request->input('year');

        // buat list
        $my_array1 = explode(" ", $my_name);
        $my_array2 = str_split($my_year); 

        // function str_contains php , biar gak error str_contains-nya
        if (!function_exists('str_contains')) {
            function str_contains(string $haystack, string $needle): bool
            {
                return '' === $needle || false !== strpos($haystack, $needle);
            }
        }
        // end function

        // deklarasi
        $rows = count($my_array2); //panjang angka
        $tiga_digit = $my_array2[2]; //tiga_digit_terakhir dari tahun lahir
        $empat_digit = $my_array2[3]; //empat_digit_terakhir dari tahun lahir

        // perulangan
        for ($i = 0; $i < $rows; $i++) {
            // jika 3 digit tahun lahir 2,4,6 maka nilai tersebut dikali 10,
            $y = strlen($my_array1[0]);
            $temp = 0;
            $temp_1 = "";
            if (str_contains($tiga_digit, '2') || str_contains($tiga_digit, '4') || str_contains($tiga_digit, '6')) {
                $temp = $tiga_digit * 10;
                // jika 4 digit terakhir dari tahun lahir 1,3,5 maka jumlahkan seluruhnya
                if (str_contains($empat_digit, '1') || str_contains($empat_digit, '3') || str_contains($empat_digit, '5')) {
                    $temp_1 = $empat_digit + 1 + 3 + 5;
                }
                // jika 7 , 9 maka jumlahkan 1 9 7 9
                if (str_contains($empat_digit, '7') || str_contains($empat_digit, '9')) {
                    $temp_1 = 1+9+7+9;
                }
                // return $ambil = print_r($temp+""+$temp_1);
            }
            // jika bukan 2,4,6 maka nilai dikali 11
            else {
                $temp = $tiga_digit * 11;
                // jika 4 digit terakhir dari tahun lahir 1,3,5 maka jumlahkan seluruhnya
                if (str_contains($empat_digit, '1') || str_contains($empat_digit, '3') || str_contains($empat_digit, '5')) {
                    $temp_1 = $empat_digit + 1 + 3 + 5;
                }
                // jika 7 , 9 maka jumlahkan 1 9 7 9
                if (str_contains($empat_digit, '7') || str_contains($empat_digit, '9')) {
                    $temp_1 = 1+9+7+9;
                }
            }
            $value = "{$y}{$temp}{$temp_1}";
        }
        // convert decimal to biner
        $text = $value;
        $response = "";
        for ($i = 0; $i < strlen($text); $i++) {
            $response .= sprintf("%06d ", decbin(ord($text[$i])));
        }
        $value_1 = "{$value}";
        // print_r("binary asli :".decbin($value)."\n");
        $binary_1 = "{$response}"; // 01101000 01100101 01101100 01101100 01101111
        // end biner

        if ($validasi->fails()) {
            return response()->json([
                'pesan' => 'data gagal ditambahkan!ada error',
                'data' => $validasi->errors()->all()
            ], 404);
        }

        // create inputan ke db
        Validate_me::create($request->all());
        // response json
        return response()->json([
            'value' => $value_1,
            'binary' => $binary_1,
        ], 200);
    }
}
