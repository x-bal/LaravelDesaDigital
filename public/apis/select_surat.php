<?php
require_once 'includes/Constants.php';
$query = mysqli_query($con, "select
permohonan_surats.*,
jenis_surats.jenis_surat,
wargas.id as wargas_id,
wargas.desa_id,
wargas.kecamatan_id,
wargas.kabupaten_id,
wargas.user_id,
wargas.nik,
wargas.nama_warga,
wargas.jenis_kelamin,
wargas.tempat_lahir,
wargas.tanggal_lahir,
wargas.is_nik,
desas.id as desas_id ,desas.kecamatan_id,desas.nama_desa,desas.background,
kecamatans.id as kecamatans_id,kecamatans.kabupaten_id,kecamatans.nama_kecamatan,
kabupatens.id as kabupatens_id,kabupatens.provinsi_id,kabupatens.nama_kabupaten
from
permohonan_surats
inner join jenis_surats
on
jenis_surats.id = permohonan_surats.jenis_surat_id 
inner join wargas
on
permohonan_surats.warga_id = wargas.id
left join desas 
on
wargas.desa_id = desas.id
left join kecamatans
on
wargas.kecamatan_id = kecamatans.id		
left join kabupatens
on
wargas.kabupaten_id = kabupatens.id");
$data = array();
$qry_array = array();
$i = 0;
$total = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query)) {
  $data['id'] = $row['id'];
  $data['jenis_surat'] = $row['jenis_surat'];
  $data['nama_warga'] = $row['nama_warga'];
  $data['nama_desa'] = $row['nama_desa'];
  $data['created_at'] = $row['created_at'];
  $qry_array[$i] = $data;
  $i++;
}

if($query){
  $response['success'] = 'true';
  $response['message'] = 'Data Loaded Successfully';
  $response['total'] = $total;
  $response['data'] = $qry_array;
}else{
  $response['success'] = 'false';
  $response['message'] = 'Data Loading Failed';
}

echo json_encode($response);
?>
