<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

            $id_surat = $_REQUEST['id_surat'];
            $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
            $no = 1;
            list($id_surat) = mysqli_fetch_array($query);

            //validasi form kosong
            if($_REQUEST['tujuan'] == "" || $_REQUEST['isi_disposisi'] == "" || $_REQUEST['sifat'] == "" || $_REQUEST['batas_waktu'] == ""
                || $_REQUEST['catatan'] == ""){
                $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                $tujuan = $_REQUEST['tujuan'];
                $isi_disposisi = $_REQUEST['isi_disposisi'];
                $sifat = $_REQUEST['sifat'];
                $batas_waktu = $_REQUEST['batas_waktu'];
                $catatan = $_REQUEST['catatan'];
                $id_user = $_SESSION['id_user'];

                //validasi input data
                if(!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $tujuan)){
                    $_SESSION['tujuan'] = 'Form Tujuan Disposisi hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,) minus(-). kurung() dan garis miring(/)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if(!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $isi_disposisi)){
                        $_SESSION['isi_disposisi'] = 'Form Isi Disposisi hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan(&), underscore(_), kurung(), persen(%) dan at(@)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if(!preg_match("/^[0-9 -]*$/", $batas_waktu)){
                            $_SESSION['batas_waktu'] = 'Form Batas Waktu hanya boleh mengandung karakter huruf dan minus(-)<br/>';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if(!preg_match("/^[a-zA-Z0-9.,()%@\/ -]*$/", $catatan)){
                                $_SESSION['catatan'] = 'Form catatan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-) garis miring(/), dan kurung()';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                if(!preg_match("/^[a-zA-Z0 ]*$/", $sifat)){
                                    $_SESSION['sifat'] = 'Form SIFAT hanya boleh mengandung karakter huruf dan spasi';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    $query = mysqli_query($config, "INSERT INTO tbl_disposisi(tujuan,isi_disposisi,sifat,batas_waktu,catatan,id_surat,id_user, status)
                                        VALUES('$tujuan','$isi_disposisi','$sifat','$batas_waktu','$catatan','$id_surat','$id_user', '1')");

                                    $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET approval_superadmin='1',status_feedback='0', status_disposisi = '1',id_user='$tujuan' WHERE id_surat='$id_surat'");

                                    if($query == true){
                                        $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                                        echo '<script language="javascript">
                                                window.location.href="./admin.php?page=tsm&act=disp&id_surat='.$id_surat.'";
                                              </script>';
                                    } else {
                                        $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">description</i> Tambah Disposisi Surat</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
                if(isset($_SESSION['errQ'])){
                    $errQ = $_SESSION['errQ'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errQ.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['errQ']);
                }
                if(isset($_SESSION['errEmpty'])){
                    $errEmpty = $_SESSION['errEmpty'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errEmpty.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['errEmpty']);
                }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">

                <!-- Form START -->
                <form class="col s12" method="post" action="">

                    <!-- Row in form START -->
                    <div class="row">
                       <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">place</i><label>Tujuan Disposisi</label><br/>
                            <div class="input-field col s11 right">
                                <select class="browser-default validate" name="tujuan" id="sifat" required>
                                    <option value="">-Silahkan Pilih Tujuan Disposisi</option>
                                     <?php 
                                      $location = mysqli_query($config,"SELECT * FROM tbl_user where admin=5"); 
                                      while ($location_ft = mysqli_fetch_array($location)) {   
                                      ?>
                                      <option value="<?php echo $location_ft["id_user"]; ?>"><?php echo $location_ft["nama"]; ?></option>
                                      <?php
                                        }
                                       ?>
                                       <?php 
                                      $location = mysqli_query($config,"SELECT * FROM tbl_user where admin=6"); 
                                      while ($location_ft = mysqli_fetch_array($location)) {   
                                      ?>
                                      <option value="<?php echo $location_ft["id_user"]; ?>"><?php echo $location_ft["nama"]; ?></option>
                                      <?php
                                        }
                                       ?>
                                       <?php 
                                      $location = mysqli_query($config,"SELECT * FROM tbl_user where admin=7"); 
                                      while ($location_ft = mysqli_fetch_array($location)) {   
                                      ?>
                                      <option value="<?php echo $location_ft["id_user"]; ?>"><?php echo $location_ft["nama"]; ?></option>
                                      <?php
                                        }
                                       ?>
                                       <?php 
                                      $location = mysqli_query($config,"SELECT * FROM tbl_user where admin=3"); 
                                      while ($location_ft = mysqli_fetch_array($location)) {   
                                      ?>
                                      <option value="<?php echo $location_ft["id_user"]; ?>"><?php echo $location_ft["nama"]; ?></option>
                                      <?php
                                        }
                                       ?>
                                </select>
                            </div>
                            <?php
                                if(isset($_SESSION['tujuan'])){
                                    $sifat = $_SESSION['tujuan'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$sifat.'</div>';
                                    unset($_SESSION['tujuan']);
                                }
                            ?>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">alarm</i>
                            <input id="batas_waktu" type="text" name="batas_waktu" class="datepicker" required>
                                <?php
                                    if(isset($_SESSION['batas_waktu'])){
                                        $batas_waktu = $_SESSION['batas_waktu'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$batas_waktu.'</div>';
                                        unset($_SESSION['batas_waktu']);
                                    }
                                ?>
                            <label for="batas_waktu">Batas Waktu</label>
                        </div>
                        
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list   </i>
                            <input id="catatan" type="text" class="validate" name="catatan" required>
                                <?php
                                    if(isset($_SESSION['catatan'])){
                                        $catatan = $_SESSION['catatan'];
                                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$catatan.'</div>';
                                        unset($_SESSION['catatan']);
                                    }
                                ?>
                            <label for="catatan">Catatan</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">low_priority</i><label>Pilih Sifat Disposisi</label><br/>
                            <div class="input-field col s11 right">
                                <select class="browser-default validate" name="sifat" id="sifat" required>
                                    <option value="Biasa">Biasa</option>
                                    <option value="Penting">Penting</option>
                                    <option value="Segera">Segera</option>
                                    <option value="Rahasia">Rahasia</option>
                                </select>
                            </div>
                            <?php
                                if(isset($_SESSION['sifat'])){
                                    $sifat = $_SESSION['sifat'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$sifat.'</div>';
                                    unset($_SESSION['sifat']);
                                }
                            ?>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">low_priority</i><label>Pilih isi Disposisi</label><br/>
                            <div class="input-field col s11 right">
                                <select class="browser-default validate" name="isi_disposisi" id="isi_disposisi" required>
                                    <option value="Tindak Lanjuti Sesuai Ketentuan">Tindak lanjuti sesuai ketentuan</option>
                                    <option value="Pelajari, Teliti dan Dilaporkan">Pelajari, teliti dan dilaporkan</option>
                                    <option value="Buatkan Tanggapan / Kajian / Saran">Buatkan tanggapan / Kajian / Saran</option>
                                    <option value="Untuk Di Pedomani">Untuk di pedomani</option>
                                    <option value="Buatkan Materi / Sambutan / Jawaban">Buatkan materi / Sambutan / Jawaban</option>
                                    <option value="Untuk Menjadi Perhatian / Diketahui">Untuk menjadi perhatian / Diketahui</option>
                                    <option value="Wakili / Hadiri">Wakili / Hadiri</option>
                                    <option value="Monitor Pelaksanaan">Monitor pelaksanaan</option>
                                    <option value="File Khusus">File khusus</option>

                                </select>
                            </div>
                            <?php
                                if(isset($_SESSION['isi_disposisi'])){
                                    $sifat = $_SESSION['isi_disposisi'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$isi_disposisi.'</div>';
                                    unset($_SESSION['isi_disposisi']);
                                }
                            ?>
                        </div>
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col 6">
                            <button type="submit" name ="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                        </div>
                        <div class="col 6">
                            <button type="reset" onclick="window.history.back();" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></button>
                        </div>
                    </div>

                </form>
                <!-- Form END -->

            </div>
            <!-- Row form END -->

<?php
        }
    }
?>
