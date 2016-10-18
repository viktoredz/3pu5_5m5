<div style="padding:5px;text-align:center">
<form method="POST" id="frmData">
	<button type="button" class=btn onClick="save_{action}_dialog($('#frmData').serialize());">Simpan</button>
	<button type="reset"  class=btn>Ulang</button>
	<button type="button" class=btn onClick="close_dialog();">Batal</button>
	<br />
	<br />
	<table border="0" cellpadding="0" cellspacing="8" class="panel" align="center">
		<tr>
			<td>

				<table border="0" cellpadding="3" cellspacing="2">
					<tr>
						<td>ID</td>
						<td>:</td>
						<td><?php if($action=="add"){ ?>
								{auto-number}
							<?php }else{?>
								<input class=input type="text" size="10" name="id" readonly value="<?php 
								if(set_value('id')=="" && isset($id)){
								 	echo $id;
								}else{
									echo  set_value('id');
								}
								?>"/>
							<?php }?>
						</td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>
                            <select size="1" name="id_jabatan" class="input">
                                <?php
                                    $data=$this->admin_master_penanggungjawab_model->get_all_jabatan();
                                    foreach($data as $row){
                                        if(set_value('id_jabatan')=="" && isset($id_jabatan)){
                                            ?>
                                                <option value="<?php echo $row->id_jabatan; ?>" <?php if($row->id_jabatan==$id_jabatan) echo "selected"; ?>><?php echo $row->nama_jabatan; ?></option>
                                            <?php
                                        }else{
                                            ?>
                                                <option value="<?php echo $row->id_jabatan; ?>"><?php echo $row->nama_jabatan; ?></option>
                                            <?php
                                        }
                                    }   
                                ?>
                                
                                
                            </select>
						</td>
					</tr>
					<tr>
						<td>NIP</td>
						<td>:</td>
						<td><input class=input type="text" size="30" name="nip" value="<?php 
								if(set_value('nip')=="" && isset($nip)){
								 	echo $nip;
								}else{
									echo  set_value('nip');
								}
								 ?>"/>
						</td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td><input class=input type="text" size="30" name="nama" value="<?php 
								if(set_value('nama')=="" && isset($nama)){
								 	echo $nama;
								}else{
									echo  set_value('nama');
								}
								 ?>"/>
						</td>
					</tr>
					<tr>
						<td>Active</td>
						<td>:</td>
						<td>   <?php 
								if(set_value('status')=="" && isset($status)){
								?>
                                    <input type="checkbox" class="input" name="status" value="1" <?php if($status=='1') echo "checked"; ?>/>
                                <?php    
								}else{
								?>
                                    <input type="checkbox" class="input" name="status" value="1"/>
                                <?php
								}
								 ?>
						</td>
					</tr>
					<tr>
						<td colspan="3" height="30"></td>
					</tr>
				</table>
            </td>
		</tr>
	</table>
</form>
</div>