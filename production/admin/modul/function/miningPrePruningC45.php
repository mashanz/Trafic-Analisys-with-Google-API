<?php
//include "0dbconnect.php";
populateDb(); 
miningC45(null, null);
updateKeputusanUnknown();
generateRuleFinalPrePruning();
insertRuleC45PrePruning();
populateAtribut2();
	echo "<br><font face='Courier New' size='2'>";
	get_subfolder('0', 0);
	echo "</font><br>";


function lihatPerhitungan() {
    $sqlInfGainMaxIterasi = mysql_query("SELECT distinct atribut, gain_ratio FROM mining_c45 WHERE gain_ratio in (SELECT max(gain_ratio) FROM `mining_c45`) LIMIT 1");
    $rowInfGainMaxIterasi = mysql_fetch_array($sqlInfGainMaxIterasi);
    // hanya ambil atribut dimana prilaku kasus totalnya Tidak kosong
    if ($rowInfGainMaxIterasi['gain_ratio'] > 0) {
	echo "<br><font face='Courier New' size='2'>";
	get_subfolder('0', 0);
	echo "</font><br>";
        echo " <table class='table table-striped jambo_table bulk_action'>
           <tr>
               <th>No</th>
               <th>Atribut</th>
               <th>Nilai Atribut</th>
               <th>Jumlah Kasus Total</th>
               <th>Jumlah Kasus Lancar</th>
               <th>Jumlah Kasus Padat</th>
               <th>Jumlah Kasus Macet</th>
               <th>Entropy</th>
               <th>Information Gain</th>
               <th>Split Info</th>
               <th>Gain Ratio</th>
           </tr>";
        $no = 1;
        $sql=mysql_query("SELECT * FROM mining_c45 ORDER BY id");
        $warna1 = '#FFFFFF';
        $warna2 = '#CCFFFF'; 
        $warna  = $warna1; 

        while ($data=mysql_fetch_array($sql)){
            if($warna == $warna1){ 
                $warna = $warna2; 
            } else { 
                $warna = $warna1; 
            } 
            echo " <tr bgcolor='$warna'>
                       <td>$no</td>
                       <td>$data[atribut]</td>
                       <td>$data[nilai_atribut]</td>
                       <td>$data[jml_kasus_total]</td>
                       <td>$data[jml_kasus_lancar]</td>
                       <td>$data[jml_kasus_padat]</td>
                       <td>$data[jml_kasus_macet]</td>
                       <td>$data[entropy]</td>
                       <td>$data[inf_gain]</td>
                       <td>$data[split_info]</td>
                       <td>$data[gain_ratio]</td>
                   </tr>";
        $no++;
        }
        echo"</table>";
		echo "<br><p>Atribut <b>$rowInfGainMaxIterasi[atribut]</b> memiliki nilai gain terbesar</p>";
    }
}

//---------- KUMPULAN FUNGSI YANG AKAN DILAKUKAN DALAM PROSES MINING ----------
function miningC45($atribut, $nilai_atribut)
{
    perhitunganC45($atribut, $nilai_atribut);
    lihatPerhitungan();
    insertAtributPohonKeputusan($atribut, $nilai_atribut);
    getInfGainMax($atribut, $nilai_atribut);
}

function selectDataFromDb($select, $tabel, $where) {
    $sqlData = mysql_query("SELECT $select as data FROM $tabel WHERE $where");
    $rowData = mysql_fetch_array($sqlData);
    return $rowData['data'];
}

function insertAtribut($atribut) {
    $sqlAtribut = mysql_query("SELECT distinct `$atribut` FROM data_kemacetan WHERE status_data = 'Data Training' ORDER by `$atribut`");
    while ($rowAtribut = mysql_fetch_array($sqlAtribut)) {
        mysql_query("insert into `atribut` values
                    ('', '$atribut', '$rowAtribut[$atribut]')");
        // echo "insert into `atribut` values
                    // ('', '$atribut', '$rowAtribut[$atribut]')<br>";
    }
}

function hapusAtributParent($id) {
    $sqlAtribut = mysql_query("SELECT id_parent, atribut, nilai_atribut FROM pohon_keputusan_c45 WHERE id = '$id'");
    $rowAtribut = mysql_fetch_array($sqlAtribut);
    mysql_query("DELETE FROM atribut WHERE atribut = '$rowAtribut[atribut]'");
    if ($rowAtribut['id_parent'] == 0) {
        echo "";
    } else {
        hapusAtributParent($rowAtribut['id_parent']);
    }
}

//#1# Hapus semua DB dan insert default atribut dan nilai atribut

function populateAtribut() 
{
    mysql_query("TRUNCATE atribut");
    mysql_query("insert into `atribut` values
                ('', 'Total', 'Total')
                ");
    insertAtribut('lancar');
    insertAtribut('padat');
    insertAtribut('macet');
}

function populateAtribut2() 
{
    mysql_query("TRUNCATE atribut");
    insertAtribut('lancar');
    insertAtribut('padat');
    insertAtribut('macet');
}

function populateDb() 
{
    //#1# Hapus semua DB dan insert default atribut dan nilai atribut
    mysql_query("DELETE FROM rule_penentu_keputusan WHERE pohon = 'C45'");
    mysql_query("TRUNCATE pohon_keputusan_c45");
    mysql_query("TRUNCATE rule_c45");
    mysql_query("TRUNCATE mining_c45");
    mysql_query("TRUNCATE iterasi_c45");
    populateAtribut();
}

// ================ FUNGSI PERHITUNGAN C45 =================
function perhitunganC45($atribut, $nilai_atribut) 
{
    if (empty($atribut) AND empty($nilai_atribut)) {
//#2# Jika atribut yg diinputkan kosong, maka lakukan perhitungan awal
        $kondisiAtribut = ""; // set kondisi atribut kosong
    } else if (!empty($atribut) AND !empty($nilai_atribut)) { 
        // jika atribut tdk kosong, maka select kondisi atribut dari DB
        $sqlKondisiAtribut = mysql_query("SELECT kondisi_atribut FROM pohon_keputusan_c45 WHERE atribut = '$atribut' AND nilai_atribut = '$nilai_atribut' order by id DESC LIMIT 1");
        $rowKondisiAtribut = mysql_fetch_array($sqlKondisiAtribut);
        $kondisiAtribut = str_replace("~", "'", $rowKondisiAtribut['kondisi_atribut']); // replace string ~ menjadi '
    } 
    
    // ambil seluruh atribut
    $sqlAtribut = mysql_query("SELECT distinct atribut FROM atribut");
    while($rowGetAtribut = mysql_fetch_array($sqlAtribut)) {
        $getAtribut = $rowGetAtribut['atribut'];
        if ($getAtribut === 'Total') { 
            $sqlJumlahKasusTotal = mysql_query("SELECT COUNT(*) as jumlah_total FROM data_kemacetan WHERE kondisi is not null AND status_data = 'Data Training' $kondisiAtribut");
            $rowJumlahKasusTotal = mysql_fetch_array($sqlJumlahKasusTotal);
            $getJumlahKasusTotal = $rowJumlahKasusTotal['jumlah_total'];

            // hitung Jumlah Kasus Lancar
            $sqlJumlahKasusLancar = mysql_query("SELECT COUNT(*) as jumlah_lancar FROM data_kemacetan WHERE kondisi = 'Lancar' AND kondisi is not null AND status_data = 'Data Training' $kondisiAtribut");
            $rowJumlahKasusLancar = mysql_fetch_array($sqlJumlahKasusLancar);
            $getJumlahKasusLancar = $rowJumlahKasusLancar['jumlah_lancar'];

            // hitung Jumlah Kasus Padat
            $sqlJumlahKasusPadat = mysql_query("SELECT COUNT(*) as jumlah_padat FROM data_kemacetan WHERE kondisi = 'Padat' AND kondisi is not null AND status_data = 'Data Training' $kondisiAtribut");
            $rowJumlahKasusPadat = mysql_fetch_array($sqlJumlahKasusPadat);
            $getJumlahKasusPadat = $rowJumlahKasusPadat['jumlah_padat'];

            // hitung Jumlah Kasus Macet
            $sqlJumlahKasusMacet = mysql_query("SELECT COUNT(*) as jumlah_macet FROM data_kemacetan WHERE kondisi = 'Macet' AND kondisi is not null AND status_data = 'Data Training' $kondisiAtribut");
            $rowJumlahKasusMacet = mysql_fetch_array($sqlJumlahKasusMacet);
            $getJumlahKasusMacet = $rowJumlahKasusMacet['jumlah_macet'];

            // hitung Jumlah Tidak Bisa Lewat
            $sqlJumlahKasusTidakBisaLewat = mysql_query("SELECT COUNT(*) as jumlah_tidak_bisa_lewat FROM data_kemacetan WHERE kondisi = 'Tidak Bisa Lewat' AND kondisi is not null AND status_data = 'Data Training' $kondisiAtribut");
            $rowJumlahKasusTidakBisaLewat = mysql_fetch_array($sqlJumlahKasusTidakBisaLewat);
            $getJumlahKasusTidakBisaLewat = $rowJumlahKasusTidakBisaLewat['jumlah_tidak_bisa_lewat'];

        //#4# Insert jumlah kasus ke DB
            // insert ke database mining_c45
            mysql_query("INSERT INTO mining_c45 VALUES ('', 'Total', 'Total', '$getJumlahKasusTotal', '$getJumlahKasusLancar', '$getJumlahKasusPadat', 
            '$getJumlahKasusMacet', '$getJumlahKasusTidakBisaLewat', '', '', '', '', '', '')");
        } else {
//#5# Jika atribut != total (atribut lainnya), maka hitung jumlah kasus total, jumlah kasus lainnya
            // ambil nilai atribut
            $sqlNilaiAtribut = mysql_query("SELECT nilai_atribut FROM atribut WHERE atribut = '$getAtribut' ORDER BY id");
            while($rowNilaiAtribut = mysql_fetch_array($sqlNilaiAtribut)) {
                $getNilaiAtribut = $rowNilaiAtribut['nilai_atribut'];

                // set kondisi dimana nilai_atribut = berdasakan masing2 atribut dan status data = data training
                $kondisi = "$getAtribut = '$getNilaiAtribut' AND kondisi is not null AND status_data = 'Data Training' $kondisiAtribut";

                // hitung jumlah kasus per atribut
                $sqlJumlahKasusTotalAtribut = mysql_query("SELECT COUNT(*) as jumlah_total FROM data_kemacetan WHERE $kondisi");
                $rowJumlahKasusTotalAtribut = mysql_fetch_array($sqlJumlahKasusTotalAtribut);
                $getJumlahKasusTotalAtribut = $rowJumlahKasusTotalAtribut['jumlah_total'];

                // hitung Jumlah Kasus Lancar
                $sqlJumlahKasusLancarAtribut = mysql_query("SELECT COUNT(*) as jumlah_lancar FROM data_kemacetan WHERE $kondisi AND kondisi = 'Lancar'");
                $rowJumlahKasusLancarAtribut = mysql_fetch_array($sqlJumlahKasusLancarAtribut);
                $getJumlahKasusLancarAtribut = $rowJumlahKasusLancarAtribut['jumlah_lancar'];

                // hitung Jumlah Kasus Macet
                $sqlJumlahKasusPadatAtribut = mysql_query("SELECT COUNT(*) as jumlah_padat FROM data_kemacetan WHERE $kondisi AND kondisi = 'Padat'");
                $rowJumlahKasusPadatAtribut = mysql_fetch_array($sqlJumlahKasusPadatAtribut);
                $getJumlahKasusPadatAtribut = $rowJumlahKasusPadatAtribut['jumlah_padat'];

                // hitung Jumlah Kasus Macet Sekali
                $sqlJumlahKasusMacetAtribut = mysql_query("SELECT COUNT(*) as jumlah_macet FROM data_kemacetan WHERE $kondisi AND kondisi = 'Macet'");
                $rowJumlahKasusMacetAtribut = mysql_fetch_array($sqlJumlahKasusMacetAtribut);
                $getJumlahKasusMacetAtribut = $rowJumlahKasusMacetAtribut['jumlah_macet'];

                // hitung Jumlah Kasus Tidak Bisa Lewat
                $sqlJumlahKasusTidakBisaLewatAtribut = mysql_query("SELECT COUNT(*) as jumlah_tidak_bisa_lewat FROM data_kemacetan WHERE $kondisi AND kondisi = 'Tidak Bisa Lewat'");
                $rowJumlahKasusTidakBisaLewatAtribut = mysql_fetch_array($sqlJumlahKasusTidakBisaLewatAtribut);
                $getJumlahKasusTidakBisaLewatAtribut = $rowJumlahKasusTidakBisaLewatAtribut['jumlah_tidak_bisa_lewat'];

//#6# Insert jumlah kasus total ke DB
                // insert ke database mining_c45
                mysql_query("INSERT INTO mining_c45 VALUES ('', '$getAtribut', '$getNilaiAtribut', '$getJumlahKasusTotalAtribut', '$getJumlahKasusLancarAtribut', '$getJumlahKasusPadatAtribut', 
                '$getJumlahKasusMacetAtribut', '$getJumlahKasusTidakBisaLewatAtribut', '', '', '', '', '', '')");
                
//#7# Lakukan perhitungan entropy
                // perhitungan entropy
                $sqlEntropy = mysql_query("SELECT id, jml_kasus_total, jml_kasus_lancar, jml_kasus_padat, jml_kasus_macet FROM mining_c45");
                while($rowEntropy = mysql_fetch_array($sqlEntropy)) {
                    $getJumlahKasusTotalEntropy = $rowEntropy['jml_kasus_total'];
                    $getJumlahKasusLancarEntropy = $rowEntropy['jml_kasus_lancar'];
                    $getJumlahKasusPadatEntropy = $rowEntropy['jml_kasus_padat'];
                    $getJumlahKasusMacetEntropy = $rowEntropy['jml_kasus_macet'];
                    $getJumlahKasusTidakBisaLewatEntropy = $rowEntropy['jml_kasus_tidak_bisa_lewat'];
                    $idEntropy = $rowEntropy['id'];

                    if ($getJumlahKasusLancarEntropy == $getJumlahKasusPadatEntropy AND $getJumlahKasusLancarEntropy == $getJumlahKasusMacetEntropy AND $getJumlahKasusLancarEntropy == $getJumlahKasusTidakBisaLewatEntropy) {
                        $getEntropy = 1;
                    } else { // jika jml kasus != 0, maka hitung rumus entropy: 
                        $perbandingan_lancar = $getJumlahKasusLancarEntropy / $getJumlahKasusTotalEntropy;
                        $perbandingan_padat = $getJumlahKasusPadatEntropy / $getJumlahKasusTotalEntropy;
                        $perbandingan_macet = $getJumlahKasusMacetEntropy / $getJumlahKasusTotalEntropy;
                        $perbandingan_tidak_bisa_lewat = $getJumlahKasusTidakBisaLewatEntropy / $getJumlahKasusTotalEntropy;

                        if ($getJumlahKasusLancarEntropy == 0) {
                            $perbandingan_lancarx = 0; 
                        } else {
                            $perbandingan_lancarx = (-($perbandingan_lancar) * log($perbandingan_lancar,2));
                        }
                        if ($getJumlahKasusPadatEntropy == 0) {
                            $perbandingan_padatx = 0; 
                        } else {
                            $perbandingan_padatx = (-($perbandingan_padat) * log($perbandingan_padat,2));
                        }
                        if ($getJumlahKasusMacetEntropy == 0) {
                            $perbandingan_macetx = 0; 
                        } else {
                            $perbandingan_macetx = (-($perbandingan_macet) * log($perbandingan_macet,2));
                        }
                        if ($getJumlahKasusTidakBisaLewatEntropy == 0) {
                            $perbandingan_tidak_bisa_lewatx = 0; 
                        } else {
                            $perbandingan_tidak_bisa_lewatx = (-($perbandingan_tidak_bisa_lewat) * log($perbandingan_tidak_bisa_lewat,2));
                        }
                        $rumusEntropy = ( $perbandingan_lancarx + $perbandingan_padatx + $perbandingan_macetx + $perbandingan_tidak_bisa_lewatx
                                        ) / (4/2);
                        $getEntropy = round($rumusEntropy,4); 
                    }
//#8# Update nilai entropy
                    // update nilai entropy
                    mysql_query("UPDATE mining_c45 SET entropy = $getEntropy WHERE id = $idEntropy");
                }
                
//#9# Lakukan perhitungan information gain
                // perhitungan information gain
                // ambil nilai entropy dari total (jumlah kasus total)
                $sqlJumlahKasusTotalInfGain = mysql_query("SELECT jml_kasus_total, entropy FROM mining_c45 WHERE atribut = 'Total'");
                $rowJumlahKasusTotalInfGain = mysql_fetch_array($sqlJumlahKasusTotalInfGain);
                $getJumlahKasusTotalInfGain = $rowJumlahKasusTotalInfGain['jml_kasus_total'];
                // rumus information gain
				if ($getEntropy == 0) {
					$getInfGain = 0;
				} else {
					$getInfGain = (-(($getJumlahKasusTotalEntropy / $getJumlahKasusTotalInfGain) * ($getEntropy))); 
				}

//#10# Update information gain tiap nilai atribut (temporary)
                // update inf_gain_temp (utk mencari nilai masing2 atribut)
                mysql_query("UPDATE mining_c45 SET inf_gain_temp = $getInfGain WHERE id = $idEntropy");
                $getEntropy = $rowJumlahKasusTotalInfGain['entropy'];

                // jumlahkan masing2 inf_gain_temp atribut 
                $sqlAtributInfGain = mysql_query("SELECT SUM(inf_gain_temp) as inf_gain FROM mining_c45 WHERE atribut = '$getAtribut'");
                while ($rowAtributInfGain = mysql_fetch_array($sqlAtributInfGain)) {
                    $getAtributInfGain = $rowAtributInfGain['inf_gain'];
                    // echo "atribut = $getAtribut | inf_gain = $getAtributInfGain <br>";

					if ($getAtributInfGain == 0) {
						// $getInfGainFix = 0;
						$getInfGainFix = $getEntropy;
					} else {
						// hitung inf gain
						$getInfGainFix = round(($getEntropy + $getAtributInfGain),4);
					}

//#11# Looping perhitungan information gain, sehingga mendapatkan information gain tiap atribut. Update information gain
                    // update inf_gain (fix)
                    mysql_query("UPDATE mining_c45 SET inf_gain = $getInfGainFix WHERE atribut = '$getAtribut'");
                } 
                
//#12# Lakukan perhitungan split info
                // rumus split info
                $getSplitInfo = (($getJumlahKasusTotalEntropy / $getJumlahKasusTotalInfGain) * (log(($getJumlahKasusTotalEntropy / $getJumlahKasusTotalInfGain),2)));
                
//#13# Update split info tiap nilai atribut (temporary)
                // update split_info_temp (utk mencari nilai masing2 atribut)
                mysql_query("UPDATE mining_c45 SET split_info_temp = $getSplitInfo WHERE id = $idEntropy");
                
                // jumlahkan masing2 split_info_temp dari tiap atribut 
                $sqlAtributSplitInfo = mysql_query("SELECT SUM(split_info_temp) as split_info FROM mining_c45 WHERE atribut = '$getAtribut'");
                while ($rowAtributSplitInfo = mysql_fetch_array($sqlAtributSplitInfo)){
                    $getAtributSplitInfo = $rowAtributSplitInfo['split_info'];

                    // split info fix (4 angka di belakang koma)
                    $getSplitInfoFix = -(round($getAtributSplitInfo,4));

//#14# Looping perhitungan split info, sehingga mendapatkan information gain tiap atribut. Update information gain
                    // update split info (fix)
                    mysql_query("UPDATE mining_c45 SET split_info = $getSplitInfoFix WHERE atribut = '$getAtribut'");
                }
            }
            
//#15# Lakukan perhitungan gain ratio
            $sqlGainRatio = mysql_query("SELECT id, inf_gain, split_info FROM mining_c45");
            while($rowGainRatio = mysql_fetch_array($sqlGainRatio)) {
                $idGainRatio = $rowGainRatio['id'];
                // jika nilai inf gain == 0 dan split info == 0, maka gain ratio = 0
                if ($rowGainRatio['inf_gain'] == 0 AND $rowGainRatio['split_info'] == 0){
                    $getGainRatio = 0;
                } else {
                    // rumus gain ratio
                    $getGainRatio = round(($rowGainRatio['inf_gain'] / $rowGainRatio['split_info']),4);
                }
                
//#16# Update gain ratio dari setiap atribut
                mysql_query("UPDATE mining_c45 SET gain_ratio = $getGainRatio WHERE id = '$idGainRatio'");
            }
        }
    }
}

//#17# Insert atribut dgn information gain max ke DB pohon keputusan
function insertAtributPohonKeputusan($atribut, $nilai_atribut)
{
    // ambil nilai inf gain terMacet Sekali dimana hanya 1 atribut saja yg dipilih
    $sqlInfGainMaxTemp = mysql_query("SELECT distinct atribut, gain_ratio FROM mining_c45 WHERE gain_ratio in (SELECT max(gain_ratio) FROM `mining_c45`) LIMIT 1");
    $rowInfGainMaxTemp = mysql_fetch_array($sqlInfGainMaxTemp);
    // hanya ambil atribut dimana prilaku kasus totalnya Tidak kosong
    if ($rowInfGainMaxTemp['gain_ratio'] > 0) {
    
        // ambil nilai atribut yang memiliki nilai inf gain max
        $sqlInfGainMax = mysql_query("SELECT * FROM mining_c45 WHERE atribut = '$rowInfGainMaxTemp[atribut]'");
        while($rowInfGainMax = mysql_fetch_array($sqlInfGainMax)) {
            if ($rowInfGainMax['jml_kasus_lancar'] == 0 AND $rowInfGainMax['jml_kasus_padat'] == 0 AND $rowInfGainMax['jml_kasus_macet'] == 0 AND $rowInfGainMax['jml_kasus_tidak_bisa_lewat'] == 0) {
                $keputusan = 'Null'; 
            } else if ($rowInfGainMax['jml_kasus_lancar'] !== 0 AND $rowInfGainMax['jml_kasus_padat'] == 0 AND $rowInfGainMax['jml_kasus_macet'] == 0 AND $rowInfGainMax['jml_kasus_tidak_bisa_lewat'] == 0) {
                $keputusan = 'Lancar'; 
            } else if ($rowInfGainMax['jml_kasus_lancar'] == 0 AND $rowInfGainMax['jml_kasus_padat'] !== 0 AND $rowInfGainMax['jml_kasus_macet'] == 0 AND $rowInfGainMax['jml_kasus_tidak_bisa_lewat'] == 0) {
                $keputusan = 'Padat'; 
            } else if ($rowInfGainMax['jml_kasus_lancar'] == 0 AND $rowInfGainMax['jml_kasus_padat'] == 0 AND $rowInfGainMax['jml_kasus_macet'] !== 0 AND $rowInfGainMax['jml_kasus_tidak_bisa_lewat'] == 0) {
                $keputusan = 'Macet'; 
            } else if ($rowInfGainMax['jml_kasus_lancar'] == 0 AND $rowInfGainMax['jml_kasus_padat'] == 0 AND $rowInfGainMax['jml_kasus_macet'] == 0 AND $rowInfGainMax['jml_kasus_tidak_bisa_lewat'] !== 0) {
                $keputusan = 'Tidak Bisa Lewat'; 
            } else {
                $keputusan = '?'; 
            }
            
            if (empty($atribut) AND empty($nilai_atribut)) {
//#18# Jika atribut yang diinput kosong (atribut awal) maka insert ke pohon keputusan id_parent = 0
                // set kondisi atribut = AND atribut = nilai atribut
                $kondisiAtribut = "AND $rowInfGainMax[atribut] = ~$rowInfGainMax[nilai_atribut]~";
                // insert ke tabel pohon keputusan
                mysql_query("INSERT INTO pohon_keputusan_c45 VALUES ('', '$rowInfGainMax[atribut]', '$rowInfGainMax[nilai_atribut]', 0, '$rowInfGainMax[jml_kasus_lancar]', '$rowInfGainMax[jml_kasus_padat]', '$rowInfGainMax[jml_kasus_macet]', 
                '$rowInfGainMax[jml_kasus_tidak_bisa_lewat]', '$keputusan', 'Belum', '$kondisiAtribut', 'Belum')");
            }

//#19# Jika atribut yang diinput tidak kosong maka insert ke pohon keputusan dimana id_parent diambil dari tabel pohon keputusan sebelumnya (where atribut = atribut yang diinput)
            else if (!empty($atribut) AND !empty($nilai_atribut)) {
                $sqlIdParent = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE atribut = '$atribut' AND nilai_atribut = '$nilai_atribut' order by id DESC LIMIT 1");
                $rowIdParent = mysql_fetch_array($sqlIdParent);
                
                    mysql_query("INSERT INTO pohon_keputusan_c45 VALUES ('', '$rowInfGainMax[atribut]', '$rowInfGainMax[nilai_atribut]', $rowIdParent[id], 
                    '$rowInfGainMax[jml_kasus_lancar]', '$rowInfGainMax[jml_kasus_padat]', '$rowInfGainMax[jml_kasus_macet]', 
                    '$rowInfGainMax[jml_kasus_tidak_bisa_lewat]', '$keputusan', 'Belum', '', 'Belum')");
                    
                    /*
                    //#PRE PRUNING#
                    echo "<br>#PRE PRUNING#<br>";
                    // hitung Pessimistic error rate parent dan child 
                    echo "perhitungan pre pruning untuk parent ($rowIdParent[atribut] = $rowIdParent[nilai_atribut])<br>";
                    echo "perhitungan pre pruning untuk child ($rowInfGainMax[atribut] = $rowInfGainMax[nilai_atribut])<br>";

                    $perhitunganParentPrePruning = loopingPerhitunganPrePruning($rowIdParent['jml_kasus_lancar'], $rowIdParent['jml_kasus_padat'], $rowIdParent['jml_kasus_macet']);
                    $perhitunganChildPrePruning = loopingPerhitunganPrePruning($rowInfGainMax['jml_kasus_lancar'], $rowInfGainMax['jml_kasus_padat'], $rowInfGainMax['jml_kasus_macet']);
                    
                    echo "pessimistic error rate parent = $perhitunganParentPrePruning; pessimistic error rate child = $perhitunganChildPrePruning<br>";

                    // hitung average Pessimistic error rate child 
                    $perhitunganPessimisticChild = (($rowInfGainMax['jml_kasus_lancar'] + $rowInfGainMax['jml_kasus_padat'] + $rowInfGainMax['jml_kasus_macet']) / ($rowIdParent['jml_kasus_lancar'] + $rowIdParent['jml_kasus_padat'] + $rowIdParent['jml_kasus_macet'])) * $perhitunganChildPrePruning;
                    echo "perhitunganPessimisticChild = (($rowInfGainMax[jml_kasus_lancar] + $rowInfGainMax[jml_kasus_padat] + $rowInfGainMax[jml_kasus_macet]) / ($rowIdParent[jml_kasus_lancar] + $rowIdParent[jml_kasus_padat] + $rowIdParent[jml_kasus_macet])) * $perhitunganChildPrePruning<br>";
                    // Increment average Pessimistic error rate child
                    $perhitunganPessimisticChildIncrement += $perhitunganPessimisticChild;
                    $perhitunganPessimisticChildIncrement = round($perhitunganPessimisticChildIncrement, 4);
                    
                    // jika error rate pada child lebih besar dari error rate parent
                    if ($perhitunganPessimisticChildIncrement >= $perhitunganParentPrePruning) {
                        echo "nilai pessimistic error rate child ($perhitunganPessimisticChildIncrement) lebih besar dari pessimistic error rate parent ($perhitunganParentPrePruning),
                        sehingga child dihapus<br>";
                        // hapus child (child Tidak diinginkan)
                        mysql_query("DELETE FROM pohon_keputusan_c45 WHERE id_parent = $rowIdParent[id]");
                        
                        if ($rowIdParent['jml_kasus_lancar'] > $rowIdParent['jml_kasus_padat'] AND $rowIdParent['jml_kasus_lancar'] > $rowIdParent['jml_kasus_macet']) {
                            $keputusanPrePruning = 'Lancar';
                        } else if ($rowIdParent['jml_kasus_lancar'] < $rowIdParent['jml_kasus_padat'] AND $rowIdParent['jml_kasus_macet'] < $rowIdParent['jml_kasus_padat']) {
                            $keputusanPrePruning = 'Padat';
                        } else if ($rowIdParent['jml_kasus_lancar'] < $rowIdParent['jml_kasus_macet'] AND $rowIdParent['jml_kasus_padat'] < $rowIdParent['jml_kasus_macet']) {
                            $keputusanPrePruning = 'Macet';
                        } 
                        // update keputusan parent
                        mysql_query("UPDATE pohon_keputusan_c45 SET keputusan = '$keputusanPrePruning' where id = $rowIdParent[id]");
                    } else {
                        echo "nilai pessimistic error rate child ($perhitunganPessimisticChildIncrement) lebih kecil dari pessimistic error rate parent ($perhitunganParentPrePruning),
                        sehingga child tidak dihapus<br>";
                    }
                    */
            }
        }
    }
    loopingKondisiAtribut();
}

//#20# Lakukan looping kondisi atribut untuk diproses pada fungsi perhitunganC45()
function loopingKondisiAtribut() 
{
    // ambil semua id dan kondisi atribut
    $sqlLoopingKondisi = mysql_query("SELECT id, kondisi_atribut FROM pohon_keputusan_c45");
    while($rowLoopingKondisi = mysql_fetch_array($sqlLoopingKondisi)) {
        // select semua data dimana id_parent = id awal
        $sqlUpdateKondisi = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE id_parent = $rowLoopingKondisi[id] AND looping_kondisi = 'Belum'");
        while($rowUpdateKondisi = mysql_fetch_array($sqlUpdateKondisi)) {
            // set kondisi: kondisi sebelumnya yg diselect berdasarkan id_parent ditambah 'AND atribut = nilai atribut'
            $kondisiAtribut = "$rowLoopingKondisi[kondisi_atribut] AND $rowUpdateKondisi[atribut] = ~$rowUpdateKondisi[nilai_atribut]~";
            // update kondisi atribut
            mysql_query("UPDATE pohon_keputusan_c45 SET kondisi_atribut = '$kondisiAtribut', looping_kondisi = 'Sudah' WHERE id = $rowUpdateKondisi[id]");
        }
    }
    insertiterasi();
}

//#21# Insert iterasi nilai perhitungan ke DB
function insertiterasi()
{
    $sqlInfGainMaxIterasi = mysql_query("SELECT distinct atribut, gain_ratio FROM mining_c45 WHERE gain_ratio in (SELECT max(gain_ratio) FROM `mining_c45`) LIMIT 1");
    $rowInfGainMaxIterasi = mysql_fetch_array($sqlInfGainMaxIterasi);
    // hanya ambil atribut dimana prilaku kasus totalnya Tidak kosong
    if ($rowInfGainMaxIterasi['gain_ratio'] > 0) {
        // $kondisiAtribut = "$rowInfGainMaxTempFix[atribut]";
        $kondisiAtribut = "$rowInfGainMaxIterasi[atribut]";
        $iterasiKe = 1;
        $sqlInsertiterasiC45 = mysql_query("SELECT * FROM mining_c45");
        while($rowInsertiterasiC45 = mysql_fetch_array($sqlInsertiterasiC45)) {
            mysql_query("INSERT INTO iterasi_c45 VALUES ('', $iterasiKe, '$kondisiAtribut', '$rowInsertiterasiC45[atribut]', '$rowInsertiterasiC45[nilai_atribut]', '$rowInsertiterasiC45[jml_kasus_total]', '$rowInsertiterasiC45[jml_kasus_lancar]', '$rowInsertiterasiC45[jml_kasus_padat]', '$rowInsertiterasiC45[jml_kasus_macet]', '$rowInsertiterasiC45[jml_kasus_tidak_bisa_lewat]', '$rowInsertiterasiC45[entropy]', '$rowInsertiterasiC45[inf_gain]', '$rowInsertiterasiC45[split_info]', '$rowInsertiterasiC45[gain_ratio]')");
            $iterasiKe++;
        }
    }
    
}

//#22# Ambil information gain max untuk diproses pada fungsi loopingMiningC45()
function getInfGainMax($atribut, $nilai_atribut)
{
    // select inf gain max
    $sqlInfGainMaxAtribut = mysql_query("SELECT distinct atribut FROM mining_c45 WHERE gain_ratio in (SELECT max(gain_ratio) FROM `mining_c45` WHERE inf_gain > 0) LIMIT 1");
    while($rowInfGainMaxAtribut = mysql_fetch_array($sqlInfGainMaxAtribut)) {
        $inf_gain_max_atribut = "$rowInfGainMaxAtribut[atribut]";
        if (empty($atribut) AND empty($nilai_atribut)) {
            // jika atribut kosong, proses atribut dgn inf gain max pada fungsi loopingMiningC45()
            loopingMiningC45($inf_gain_max_atribut);
        } else if (!empty($atribut) AND !empty($nilai_atribut)) {
			// proses atribut dgn inf gain max pada fungsi loopingMiningC45()
            loopingMiningC45($inf_gain_max_atribut);
        }
    }
}

//#23# Looping proses mining dimana atribut dgn information gain max yang akan diproses pada fungsi miningC45()
function loopingMiningC45($inf_gain_max_atribut) 
{
    $sqlBelumAdaKeputusanLagi = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE keputusan = '?' and diproses = 'Belum' AND atribut = '$inf_gain_max_atribut'");
    while($rowBelumAdaKeputusanLagi = mysql_fetch_array($sqlBelumAdaKeputusanLagi)) {
        // if ($rowBelumAdaKeputusanLagi['id_parent'] == 0) {
            populateAtribut();
        // }
        $atribut = "$rowBelumAdaKeputusanLagi[atribut]";
        $nilai_atribut = "$rowBelumAdaKeputusanLagi[nilai_atribut]";
        mysql_query("TRUNCATE mining_c45");
        hapusAtributParent($rowBelumAdaKeputusanLagi['id']);
        miningC45($atribut, $nilai_atribut);
        mysql_query("UPDATE pohon_keputusan_c45 SET diproses = 'Sudah' WHERE id = '$rowBelumAdaKeputusanLagi[id]'");
    }
}

// rumus menghitung Pessimistic error rate
function perhitunganPrePruning($r, $z, $n)
{
    $rumus = ($r + (($z * $z) / (2 * $n)) + ($z * (sqrt(($r / $n) - (($r * $r) / $n) + (($z * $z) / (4 * ($n * $n))))))) / (1 + (($z * $z) / $n));
    $rumus = round($rumus, 8);
    // echo "rumus = (r + ((z * z) / (2 * n)) + (z * (sqrt((r / n) - ((r * r) / n) + ((z * z) / (4 * (n * n))))))) / (1 + ((z * z) / n)) = $rumus<br><br>";
    return $rumus;
}

// looping perhitungan Pessimistic error rate
function loopingPerhitunganPrePruning($kelas1, $kelas2, $kelas3)
{
    $z = 1.645;
    $n = $kelas1 + $kelas2; // n = total jml kasus
    $n = round($n, 4);
    // r = perbandingan child thd parent
    if ($kelas1 < $kelas2 AND $kelas1 < $kelas3) {
        $r = $kelas1 / ($n);
        $r = round($r, 4);
        return perhitunganPrePruning($r, $z, $n);
    } elseif ($kelas1 > $kelas2 AND $kelas3 > $kelas2) {
        $r = $kelas2 / ($n);
        $r = round($r, 4);
        return perhitunganPrePruning($r, $z, $n);
    } elseif ($kelas1 > $kelas3 AND $kelas2 > $kelas3) {
        $r = $kelas3 / ($n);
        $r = round($r, 4);
        return perhitunganPrePruning($r, $z, $n);
    } elseif ($kelas1 == $kelas2 AND $kelas1 == $kelas3) {
        $r = $kelas1 / ($n);
        $r = round($r, 4);
        return perhitunganPrePruning($r, $z, $n);
    }
    
    if ($kelas1 == $kelas2 AND $kelas1 < $kelas3) {
        $r = $kelas1 / ($n);
        $r = round($r, 4);
        // return perhitunganPrePruning($r, $z, $n);
        return 0;
    } elseif ($kelas2 == $kelas3 AND $kelas2 < $kelas1) {
        $r = $kelas2 / ($n);
        $r = round($r, 4);
        // return perhitunganPrePruning($r, $z, $n);
        return 0;
    } elseif ($kelas3 == $kelas1 AND $kelas3 < $kelas2) {
        $r = $kelas3 / ($n);
        $r = round($r, 4);
        // return perhitunganPrePruning($r, $z, $n);
        return 0;
    }
}

// update keputusan jika ada keputusan yg Null dan ?
function updateKeputusanUnknown()
{
    $sqlReplaceUnknownIdParent = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE keputusan = '?' and id not in (select id_parent from pohon_keputusan_c45)");
    while($rowReplaceUnknownIdParent = mysql_fetch_array($sqlReplaceUnknownIdParent)){
       if ($rowReplaceUnknownIdParent['jml_kasus_lancar'] > $rowReplaceUnknownIdParent['jml_kasus_padat'] AND $rowReplaceUnknownIdParent['jml_kasus_lancar'] > $rowReplaceUnknownIdParent['jml_kasus_macet'] AND $rowReplaceUnknownIdParent['jml_kasus_lancar'] > $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanUnknown = 'Lancar'; 
        } else if ($rowReplaceUnknownIdParent['jml_kasus_padat'] > $rowReplaceUnknownIdParent['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent['jml_kasus_padat'] > $rowReplaceUnknownIdParent['jml_kasus_macet'] AND $rowReplaceUnknownIdParent['jml_kasus_padat'] > $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanUnknown = 'Padat'; 
        } else if ($rowReplaceUnknownIdParent['jml_kasus_macet'] > $rowReplaceUnknownIdParent['jml_kasus_padat'] AND $rowReplaceUnknownIdParent['jml_kasus_macet'] > $rowReplaceUnknownIdParent['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent['jml_kasus_macet'] > $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanUnknown = 'Macet'; 
        } else if ($rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent['jml_kasus_padat'] AND $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent['jml_kasus_macet']) {
            $keputusanUnknown = 'Tidak Bisa Lewat'; 
        } else if ($rowReplaceUnknownIdParent['jml_kasus_lancar'] == $rowReplaceUnknownIdParent['jml_kasus_padat'] OR $rowReplaceUnknownIdParent['jml_kasus_lancar'] == $rowReplaceUnknownIdParent['jml_kasus_macet'] OR $rowReplaceUnknownIdParent['jml_kasus_lancar'] == $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat'] OR $rowReplaceUnknownIdParent['jml_kasus_padat'] == $rowReplaceUnknownIdParent['jml_kasus_macet'] OR $rowReplaceUnknownIdParent['jml_kasus_padat'] == $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat'] OR $rowReplaceUnknownIdParent['jml_kasus_macet'] == $rowReplaceUnknownIdParent['jml_kasus_tidak_bisa_lewat']) {
            $sqlReplaceUnknownIdParent2 = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE id = $rowReplaceUnknownIdParent[id_parent]");
            $rowReplaceUnknownIdParent2 = mysql_fetch_array($sqlReplaceUnknownIdParent2);
            if ($rowReplaceUnknownIdParent2['jml_kasus_lancar'] > $rowReplaceUnknownIdParent2['jml_kasus_padat'] AND $rowReplaceUnknownIdParent2['jml_kasus_lancar'] > $rowReplaceUnknownIdParent2['jml_kasus_macet'] AND $rowReplaceUnknownIdParent2['jml_kasus_lancar'] > $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat']) {
                $keputusanUnknown = 'Lancar'; 
            } else if ($rowReplaceUnknownIdParent2['jml_kasus_padat'] > $rowReplaceUnknownIdParent2['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent2['jml_kasus_padat'] > $rowReplaceUnknownIdParent2['jml_kasus_macet'] AND $rowReplaceUnknownIdParent2['jml_kasus_padat'] > $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat']) {
                $keputusanUnknown = 'Padat'; 
            } else if ($rowReplaceUnknownIdParent2['jml_kasus_macet'] > $rowReplaceUnknownIdParent2['jml_kasus_padat'] AND $rowReplaceUnknownIdParent2['jml_kasus_macet'] > $rowReplaceUnknownIdParent2['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent2['jml_kasus_macet'] > $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat']) {
                $keputusanUnknown = 'Macet'; 
            } else if ($rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent2['jml_kasus_padat'] AND $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent2['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent2['jml_kasus_macet']) {
                $keputusanUnknown = 'Tidak Bisa Lewat'; 
            } else if ($rowReplaceUnknownIdParent2['jml_kasus_lancar'] == $rowReplaceUnknownIdParent2['jml_kasus_padat'] OR $rowReplaceUnknownIdParent2['jml_kasus_lancar'] == $rowReplaceUnknownIdParent2['jml_kasus_macet'] OR $rowReplaceUnknownIdParent2['jml_kasus_lancar'] == $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat'] OR $rowReplaceUnknownIdParent2['jml_kasus_padat'] == $rowReplaceUnknownIdParent2['jml_kasus_macet'] OR $rowReplaceUnknownIdParent2['jml_kasus_padat'] == $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat'] OR $rowReplaceUnknownIdParent2['jml_kasus_macet'] == $rowReplaceUnknownIdParent2['jml_kasus_tidak_bisa_lewat']) {
                $sqlReplaceUnknownIdParent2 = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE id = $rowReplaceUnknownIdParent2[id_parent]");
                $rowReplaceUnknownIdParent3 = mysql_fetch_array($sqlReplaceUnknownIdParent2);
                if ($rowReplaceUnknownIdParent3['jml_kasus_lancar'] > $rowReplaceUnknownIdParent3['jml_kasus_padat'] AND $rowReplaceUnknownIdParent3['jml_kasus_lancar'] > $rowReplaceUnknownIdParent3['jml_kasus_macet'] AND $rowReplaceUnknownIdParent3['jml_kasus_lancar'] > $rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat']) {
                    $keputusanUnknown = 'Lancar'; 
                } else if ($rowReplaceUnknownIdParent3['jml_kasus_padat'] > $rowReplaceUnknownIdParent3['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent3['jml_kasus_padat'] > $rowReplaceUnknownIdParent3['jml_kasus_macet'] AND $rowReplaceUnknownIdParent3['jml_kasus_padat'] > $rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat']) {
                    $keputusanUnknown = 'Padat'; 
                } else if ($rowReplaceUnknownIdParent3['jml_kasus_macet'] > $rowReplaceUnknownIdParent3['jml_kasus_padat'] AND $rowReplaceUnknownIdParent3['jml_kasus_macet'] > $rowReplaceUnknownIdParent3['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent3['jml_kasus_macet'] > $rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat']) {
                    $keputusanUnknown = 'Macet'; 
                } else if ($rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent3['jml_kasus_padat'] AND $rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent3['jml_kasus_lancar'] AND $rowReplaceUnknownIdParent3['jml_kasus_tidak_bisa_lewat'] > $rowReplaceUnknownIdParent3['jml_kasus_macet']) {
                    $keputusanUnknown = 'Tidak Bisa Lewat'; 
                }
            }
        }
        mysql_query("UPDATE pohon_keputusan_c45 SET keputusan = '$keputusanUnknown' WHERE id = $rowReplaceUnknownIdParent[id]");
    }

    $sqlReplaceNull = mysql_query("SELECT id, id_parent FROM pohon_keputusan_c45 WHERE keputusan = 'Null'");
    while($rowReplaceNull = mysql_fetch_array($sqlReplaceNull)) {
        $sqlReplaceNullIdParent = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE id = $rowReplaceNull[id_parent]");
        $rowReplaceNullIdParent = mysql_fetch_array($sqlReplaceNullIdParent);
        if ($rowReplaceNullIdParent['jml_kasus_lancar'] > $rowReplaceNullIdParent['jml_kasus_padat'] AND $rowReplaceNullIdParent['jml_kasus_lancar'] > $rowReplaceNullIdParent['jml_kasus_macet'] AND $rowReplaceNullIdParent['jml_kasus_lancar'] > $rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanNull = 'Lancar'; 
        } else if ($rowReplaceNullIdParent['jml_kasus_padat'] > $rowReplaceNullIdParent['jml_kasus_lancar'] AND $rowReplaceNullIdParent['jml_kasus_padat'] > $rowReplaceNullIdParent['jml_kasus_macet'] AND $rowReplaceNullIdParent['jml_kasus_padat'] > $rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanNull = 'Padat'; 
        } else if ($rowReplaceNullIdParent['jml_kasus_macet'] > $rowReplaceNullIdParent['jml_kasus_padat'] AND $rowReplaceNullIdParent['jml_kasus_macet'] > $rowReplaceNullIdParent['jml_kasus_lancar'] AND $rowReplaceNullIdParent['jml_kasus_macet'] > $rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat']) {
            $keputusanNull = 'Macet'; 
        } else if ($rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNullIdParent['jml_kasus_padat'] AND $rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNullIdParent['jml_kasus_lancar'] AND $rowReplaceNullIdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNullIdParent['jml_kasus_macet']) {
            $keputusanNull = 'Tidak Bisa Lewat'; 
        }
        mysql_query("UPDATE pohon_keputusan_c45 SET keputusan = '$keputusanNull' WHERE id = $rowReplaceNull[id]");
    }

    $sqlReplaceNull2 = mysql_query("SELECT id, id_parent FROM pohon_keputusan_c45 WHERE keputusan = ''");
    while($rowReplaceNull2 = mysql_fetch_array($sqlReplaceNull2)) {
        $sqlReplaceNull2IdParent = mysql_query("SELECT * FROM pohon_keputusan_c45 WHERE id = $rowReplaceNull2[id_parent]");
        $rowReplaceNull2IdParent = mysql_fetch_array($sqlReplaceNull2IdParent);
            if ($rowReplaceNull2IdParent['jml_kasus_lancar'] > $rowReplaceNull2IdParent['jml_kasus_padat'] AND $rowReplaceNull2IdParent['jml_kasus_lancar'] > $rowReplaceNull2IdParent['jml_kasus_macet'] AND $rowReplaceNull2IdParent['jml_kasus_lancar'] > $rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat']) {
                $keputusanNull2 = 'Lancar'; 
            } else if ($rowReplaceNull2IdParent['jml_kasus_padat'] > $rowReplaceNull2IdParent['jml_kasus_lancar'] AND $rowReplaceNull2IdParent['jml_kasus_padat'] > $rowReplaceNull2IdParent['jml_kasus_macet'] AND $rowReplaceNull2IdParent['jml_kasus_padat'] > $rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat']) {
                $keputusanNull2 = 'Padat'; 
            } else if ($rowReplaceNull2IdParent['jml_kasus_macet'] > $rowReplaceNull2IdParent['jml_kasus_padat'] AND $rowReplaceNull2IdParent['jml_kasus_macet'] > $rowReplaceNull2IdParent['jml_kasus_lancar'] AND $rowReplaceNull2IdParent['jml_kasus_macet'] > $rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat']) {
                $keputusanNull2 = 'Macet'; 
            } else if ($rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNull2IdParent['jml_kasus_padat'] AND $rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNull2IdParent['jml_kasus_lancar'] AND $rowReplaceNull2IdParent['jml_kasus_tidak_bisa_lewat'] > $rowReplaceNull2IdParent['jml_kasus_macet']) {
                $keputusanNull2 = 'Tidak Bisa Lewat'; 
            }
        mysql_query("UPDATE pohon_keputusan_c45 SET keputusan = '$keputusanNull2' WHERE id = $rowReplaceNull2[id]");
    }
}

function generateRuleAwal($idparent, $spasi)
{
    // ambil data pohon keputusan
    $sqlGetIdParent = mysql_query("select * from pohon_keputusan_c45 where id_parent='$idparent'");
    while($rowGetIdParent = mysql_fetch_array($sqlGetIdParent)){
        if (!empty($rowGetIdParent)) {
            // ambil data pohon keputusan dimana id = idparent
            $sqlGetId = mysql_query("select * from pohon_keputusan_c45 where id='$rowGetIdParent[id_parent]'");
            $rowGetId = mysql_fetch_array($sqlGetId);
            // jika atribut dan nilai atribut masih kosong
            if (empty($rowGetId['atribut']) AND empty($rowGetId['nilai_atribut'])){
                // insert pada db rule_c45
                mysql_query("insert into rule_c45 values ('', '$rowGetIdParent[id_parent]', '$rowGetIdParent[atribut] == $rowGetIdParent[nilai_atribut]', '$rowGetIdParent[keputusan]')");
            } else {
                // insert pada db rule_c45
                mysql_query("insert into rule_c45 values ('', '$rowGetIdParent[id_parent]', '$rowGetId[atribut] == $rowGetId[nilai_atribut] AND $rowGetIdParent[atribut] == $rowGetIdParent[nilai_atribut]', '$rowGetIdParent[keputusan]')");
            }
            // looping dirinya sendiri
            generateRuleAwal($rowGetIdParent['id'], $spasi + 1);
        }
    }
}

function generateRuleLooping()
{
    // ambil data rule
    $sqlGetDataRule = mysql_query("select * from rule_c45 order by id");
    while($rowGetDataRule=mysql_fetch_array($sqlGetDataRule)){
        if (!empty($rowGetDataRule)) {
            // ambil idparent rule dimana id = idparent
            $sqlGetIdParentUpdateRule = mysql_query("select id_parent from pohon_keputusan_c45 where id = '$rowGetDataRule[id_parent]'");
            $rowGetIdParentUpdateRule=mysql_fetch_array($sqlGetIdParentUpdateRule);
            
            $sqlGetIdUpdateRule = mysql_query("select * from pohon_keputusan_c45 where id = '$rowGetIdParentUpdateRule[id_parent]'");
            while($rowGetIdUpdateRule=mysql_fetch_array($sqlGetIdUpdateRule)){
                // bentuk rule
                $rule = "$rowGetIdUpdateRule[atribut] == $rowGetIdUpdateRule[nilai_atribut] AND $rowGetDataRule[rule]";
                // update rule
                mysql_query("update rule_c45 set rule = '$rule', id_parent = '$rowGetIdParentUpdateRule[id_parent]' where id = '$rowGetDataRule[id]'");
            }
            
            // ambil data pohon dimana idparent = 0 (root)
            $sqlGetDataPohonKeputusan = mysql_query("select * from pohon_keputusan_c45 where id_parent = 0");
            while($rowGetDataPohonKeputusan=mysql_fetch_array($sqlGetDataPohonKeputusan)){
                // jika idparent rule == id pohon
                if ($rowGetDataRule['id_parent'] == $rowGetDataPohonKeputusan['id']){
                    // update rule set id = id rule
                    mysql_query("update rule_c45 set id_parent = 0 where id = '$rowGetDataRule[id]'");
                }
            }
        }
    }
}

function generateRuleFinalPrePruning()
{
    // panggil fungsi generateRuleAwal()
    generateRuleAwal("0", 0);
    
    // ambil data rule
    $sqlUpdateRule = mysql_query("select * from rule_c45 order by id" );
    while($rowUpdateRule=mysql_fetch_array($sqlUpdateRule)){
        if (!empty($rowUpdateRule)) {
            // jika idparent rule == 0
            if ($rowUpdateRule['id_parent'] !== 0){
                // lakukan fungsi generateRuleLooping()
                generateRuleLooping();
                // delete rule dimana keputusan == ?
                mysql_query("delete from rule_c45 where keputusan = '?'");
            }
        }
    }
}

function insertRuleC45PrePruning()
{
    // ambil data pada db rule_c45
    $sqlRuleC45 = mysql_query("SELECT id, rule, keputusan FROM rule_c45");
    while($rowRuleC45 = mysql_fetch_array($sqlRuleC45)) {
        $RuleC45 = "$rowRuleC45[rule]";
        // explode string ' AND ' utk mendapatkan atribut
        $explodeRuleC45 = explode(" AND ", $RuleC45);
        foreach ($explodeRuleC45 as $dataExplodeRuleC45) {
            // explode string ' == ' utk mendapatkan nilai atribut
            $dataFixRuleC45 = explode(" == ", $dataExplodeRuleC45);
            // insert into db
            mysql_query("INSERT INTO rule_penentu_keputusan VALUES('', $rowRuleC45[id], '$dataFixRuleC45[0]', '$dataFixRuleC45[1]', '$rowRuleC45[keputusan]', '', 'C45')");
        }
    }
}

function get_subfolder($idparent, $spasi){
    // if ($_SESSION[leveluser]=='admin'){
        $result = mysql_query("select * from pohon_keputusan_c45 where id_parent= '$idparent'");
    // }
    while($row=mysql_fetch_row($result)){
        for($i=1;$i<=$spasi;$i++){
            echo "|&nbsp;&nbsp;";
        }
        if ($row[8] === 'Lancar') {
            $keputusan = "<font color=green>$row[8]</font>";
        } elseif ($row[8] === 'Padat') {
            $keputusan = "<font color=blue>$row[8]</font>";
        } elseif ($row[8] === 'Macet') {
            $keputusan = "<font color=red>$row[8]</font>";
        } elseif ($row[8] === 'Tidak Bisa Lewat') {
            $keputusan = "<font color=purple>$row[8]</font>";
        } elseif ($row[8] === '?') {
            $keputusan = "<font color=black>$row[8]</font>";
        } else {
            $keputusan = "<b>$row[8]</b>";
        }
        echo "<font color=red>$row[1]</font> = $row[2] (Lancar = $row[4], Padat = $row[5], Macet = $row[6], Tidak Bisa Lewat = $row[7]) : <b>$keputusan</b><br>";

        /*panggil dirinya sendiri*/
        get_subfolder($row[0], $spasi + 1);
    }
}