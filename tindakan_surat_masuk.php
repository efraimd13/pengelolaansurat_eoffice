<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){
                
                $id_surat = $_REQUEST['id_surat'];
                $approvalsekre = $_REQUEST['approvalsekre'];
                $no_surat = $_REQUEST['no_surat'];
                $asal_surat = $_REQUEST['asal_surat'];
                $isi = $_REQUEST['isi'];
                $kode = substr($_REQUEST['kode'],0,30);
                $nkode = trim($kode);
                $indeks = $_REQUEST['indeks'];
                $tgl_surat = $_REQUEST['tgl_surat'];
                $keterangan = $_REQUEST['keterangan'];
                $feedback = $_REQUEST['tanggapan'];
                $status = $_REQUEST['status'];
                $id_user = $_SESSION['id_user'];

                $ekstensi = array('jpg','png','jpeg','doc','docx','pdf');
                $file = $_FILES['file']['name'];
                $x = explode('.', $file);
                $eks = strtolower(end($x));
                $ukuran = $_FILES['file']['size'];
                $target_dir = "upload/feedback/";

                if (! is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                 if($file != ""){

                     $rand = rand(1,10000);
                    $nfile = $rand."-".$file;

                                                        //validasi file
                    if(in_array($eks, $ekstensi) == true){
                        if($ukuran < 2500000){

                            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir.$nfile);

                               $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET status_feedback='$status', feedback ='$feedback', file_feedback='$nfile'  WHERE id_surat='$id_surat'");

                            if($query == true){
                                $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                                header("Location: ./admin.php?page=tsm");
                                 die();
                            } else {
                                 $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                 echo '<script language="javascript">window.history.back();</script>';
                             }
                             } else {
                                 $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                                 echo '<script language="javascript">window.history.back();</script>';
                            }
                        } else {
                            $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
                            echo '<script language="javascript">window.history.back();</script>';
                        }
                    }else{
                        $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET status_feedback='$status', feedback ='$feedback', file ='Tidak Ada File'  WHERE id_surat='$id_surat'");
                    }
            

            if($query == true){
                 $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                 header("Location: ./admin.php?page=tsm");
                 die();
             } else {
             $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                 echo '<script language="javascript">window.history.back();</script>';
            }
        } else {

        $id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);
        $query = mysqli_query($config, "SELECT id_surat, no_agenda, no_surat, asal_surat, isi, kode, indeks, tgl_surat, file, keterangan, id_user FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
        list($id_surat, $no_agenda, $no_surat, $asal_surat, $isi, $kode, $indeks, $tgl_surat, $file, $keterangan, $id_user) = mysqli_fetch_array($query);

        if($_SESSION['id_user'] != $id_user AND $_SESSION['id_user'] == 6){
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
                    window.location.href="./admin.php?page=tsm";
                  </script>';
        } else {?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">search</i> Detail Data Surat Masuk</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->


            <!-- Row form Start -->
            <div class="row jarak-form">

                <!-- Form START -->
                <form class="col s12" method="POST" action="?page=tsm&act=tindakan" enctype="multipart/form-data">

                    <!-- Row in form START -->
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="hidden" name="id_surat" value="<?php echo $id_surat ;?>">
                            <i class="material-icons prefix md-prefix">looks_one</i>
                            <input id="no_agenda" type="number" class="validate" value="<?php echo $no_agenda ;?>" name="no_agenda" required readonly>
                               
                            <label for="no_agenda">Nomor Agenda</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">bookmark</i>
                            <input id="kode" type="text" class="validate" name="kode" value="<?php echo $kode ;?>" required readonly>
                               
                            <label for="kode">Kode Klasifikasi</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">place</i>
                            <input id="asal_surat" type="text" class="validate" name="asal_surat" value="<?php echo $asal_surat ;?>" required readonly>
                               
                            <label for="asal_surat">Asal Surat</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">storage</i>
                            <input id="indeks" type="text" class="validate" name="indeks" value="<?php echo $indeks ;?>" required readonly>
                               
                            <label for="indeks">Indeks Berkas</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">looks_two</i>
                            <input id="no_surat" type="text" class="validate" name="no_surat" value="<?php echo $no_surat ;?>" required readonly>
                               
                            <label for="no_surat">Nomor Surat</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tgl_surat" type="text" name="tgl_surat" class="datepicker" value="<?php echo $tgl_surat ;?>" required readonly>
                               
                            <label for="tgl_surat">Tanggal Surat</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">description</i>
                            <textarea id="isi" class="materialize-textarea validate" name="isi" required readonly> <?php echo $isi ;?> </textarea>
                            <label for="isi">Isi Ringkas</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <input id="keterangan" type="text" class="validate" name="keterangan" value="<?php echo $keterangan ;?>" readonly required>
                               
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <textarea id="isi" class="materialize-textarea validate" name="tanggapan" required >  </textarea>
                               
                            <label for="keterangan">Tanggapan</label>
                        </div>
                        
                        <?php if ($_SESSION['admin']==3) { ?>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i> <br>
                            <select id="keterangan" class="validate" name="status" required>
                                <option value="">-Silahkan Pilih Status Surat-</option>
                                <option value="1">Sudah Diperiksa</option>
                                <option value="0">Belum Diperiksa</option>
                            </select>
                           
                               
                            <label for="keterangan">Status Surat</label>
                        </div>
                    <?php } ?>
                    <?php if ($_SESSION['admin']==7) { ?>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i> <br>
                            <select id="keterangan" class="validate" name="status" required>
                                <option value="">-Silahkan Pilih Status Surat-</option>
                                <option value="2">Sudah Diperiksa</option>
                                <option value="0">Belum Diperiksa</option>
                            </select>
                           
                               
                            <label for="keterangan">Status Surat</label>
                        </div>
                    <?php } ?>
                    <?php if ($_SESSION['admin']==6) { ?>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i> <br>
                            <select id="keterangan" class="validate" name="status" required>
                                <option value="">-Silahkan Pilih Status Surat-</option>
                                <option value="3">Sudah Diperiksa</option>
                                <option value="0">Belum Diperiksa</option>
                            </select>
                           
                               
                            <label for="keterangan">Status Surat</label>
                        </div>
                    <?php } ?>
                    <?php if ($_SESSION['admin']==5) { ?>
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i> <br>
                            <select id="keterangan" class="validate" name="status" required>
                                <option value="">-Silahkan Pilih Status Surat-</option>
                                <option value="4">Sudah Diperiksa</option>
                                <option value="0">Belum Diperiksa</option>
                            </select>
                           
                               
                            <label for="keterangan">Status Surat</label>
                        </div>
                    <?php } ?>
                         <div class="input-field col s6">
                            <div class="file-field input-field">
                                <div class="btn light-green darken-1">
                                    <span>File</span>
                                    <input type="file" id="file" name="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload file/scan gambar surat masuk">
                                        <?php
                                            if(isset($_SESSION['errSize'])){
                                                $errSize = $_SESSION['errSize'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$errSize.'</div>';
                                                unset($_SESSION['errSize']);
                                            }
                                            if(isset($_SESSION['errFormat'])){
                                                $errFormat = $_SESSION['errFormat'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$errFormat.'</div>';
                                                unset($_SESSION['errFormat']);
                                            }
                                        ?>
                                    <small class="red-text">*Format file yang diperbolehkan *.JPG, *.PNG, *.DOC, *.DOCX, *.PDF dan ukuran maksimal file 2 MB!</small>
                                </div>
                            </div>
                        </div>

                          <!-- <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <br>
                            <select class="validate" name="approvalsekre" required>
                                <option value="">-Silahkan Pilih Status Surat-</option>
                                <option value="1">Diteruskan Ke Superadmin</option>
                                <option value="2">Ditolak</option>
                            </select>
                          
                               
                            <label for="keterangan">Status Surat</label>
                        </div> -->
                      
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col 6">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                        </div>
                        <div class="col 6">
                            <a href="?page=tsm" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                        </div>
                    </div>

                </form>
                <!-- Form END -->

            </div>
            <!-- Row form END -->

<?php
            }
        }
    }
?>
