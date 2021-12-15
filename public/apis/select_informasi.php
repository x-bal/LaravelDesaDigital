<?php
require_once 'includes/Constants.php';
$query = mysqli_query($con, "select
informasis.*,
desas.id as desas_id ,desas.kecamatan_id,desas.nama_desa,desas.background,
kecamatans.id as kecamatans_id,kecamatans.kabupaten_id,kecamatans.nama_kecamatan,
kabupatens.id as kabupatens_id,kabupatens.provinsi_id,kabupatens.nama_kabupaten
from
informasis
inner join desas 
on
informasis.desa_id = desas.id
left join kecamatans
on
desas.kecamatan_id = kecamatans.id		
left join kabupatens
on
kecamatans.kabupaten_id = kabupatens.id");
$data = array();
$qry_array = array();
$i = 0;
$total = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query)) {
  $data['id'] = $row['id'];
  $data['judul'] = $row['judul'];
  $data['deskripsi'] = $row['deskripsi'];
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
