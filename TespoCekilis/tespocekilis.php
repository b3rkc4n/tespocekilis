<?php
/*
Plugin Name: Tespo.com.tr 2019 Çekiliş
Plugin URI: http://www.tespo.com.tr
Description: Tespo Çekiliş Eklentisi
Version: 201907
Author: Özgür Balcı & Berkcan Kahraman
Author URI: http://www.aytas.com.tr
License: GNU
{Plugin Name} Tespo A.Ş. ye aittir. İzin verilmedikçe kullanılamaz.
*/


add_action('wp_enqueue_scripts', 'tespo_load_scripts');
add_shortcode( 'tespocekilis', 'tespo_cekilis' );

$ekle="";

function tespo_load_scripts() {
	
	
	wp_register_style('cekilis', plugins_url('/css/cekilis.css', __FILE__));
	wp_register_script('cekilis', plugins_url('/js/cekilis.js', __FILE__), array('jquery'), false, true);
	
	

	wp_enqueue_style('cekilis');
	wp_enqueue_script('cekilis');
	
	wp_localize_script('cekilis', 'cekilis', array('pluginsUrl' => plugins_url('', __FILE__), ));
	
}

function tespo_cekilis() {
	global $wpdb;
	   if(isset($_POST['g-recaptcha-response']))
          $captcha=$_POST['g-recaptcha-response'];
    
if (!empty($_POST)) {	

if (empty($_POST['belgeno']))
	$ekle='<p style="color:red;">Fatura No eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
	else	
		if (empty($_POST['belgetrh']))
		$ekle='<p style="color:red;">Fatura Tarihi eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
		else		
			if (empty($_POST['ad']))
			$ekle='<p style="color:red;">Ad/Soyad eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
			else
				if (empty($_POST['soyad']))
				$ekle='<p style="color:red;">Ad/Soyad eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
				else
					if (empty($_POST['adres']))
					$ekle='<p style="color:red;">Adres eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
					else
						if (empty($_POST['il']))
						$ekle='<p style="color:red;">İl/İlçe eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
						else		
							if (empty($_POST['ilce']))
							$ekle='<p style="color:red;">İl/İlçe eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
							else
								if (empty($_POST['telno']))
								$ekle='<p style="color:red;">Telefon Numarası eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
								else
									if (empty($_POST['mail']))
									$ekle='<p style="color:red;">E-Posta adresi eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
									else
										if (empty($_POST['sifre']))
										$ekle='<p style="color:red;">Çekiliş şifresi eksik veya hatalı. Lütfen tekrar deneyiniz.</p>';
										else
											if (empty($_POST['katilimonay']))
											$ekle='<p style="color:red;">Katılım onayı vermediniz. Lütfen tekrar deneyiniz.</p>';
											else
    
    if (  !$captcha   ) {
        $ekle='<p style="color:red;">Ben Robot Değilim kontrolü başarısız oldu. Lütfen tekrar deneyiniz.</p>';
    } else    
    {
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdpH4oUAAAAAFPLsKjaRZTKC-1gCXHijxm00nMI&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        
        if($response['success']==false) {
            $ekle='<p style="color:red;">Ben Robot Değilim kontrolü başarısız oldu. Lütfen tekrar deneyiniz.</p>';
        } else {		
	
		
		if (isset($_POST['telonay']))
			$_POST['telonay'] = 1;
		else
			$_POST['telonay'] = 0;
		
		if (isset($_POST['mailonay']))
			$_POST['mailonay'] = 1;
		else
			$_POST['mailonay'] = 0;
		
		if (isset($_POST['katilimonay']))
			$_POST['katilimonay'] = 1;
		else
			$_POST['katilimonay'] = 0;
				
	
	
	$json_data=json_encode(
			array(
				'belgeno' => $_POST['belgeno'],
				'belgetrh' => $_POST['belgetrh'],
				'ad' => $_POST['ad'],
				'soyad' => $_POST['soyad'],
				'il' => $_POST['iltext'],
				'ilce' => $_POST['ilcetext'],
				'telno' => $_POST['telno'],
				'telonay' => $_POST['telonay'],
				'mail' => $_POST['mail'],
				'mailonay' => $_POST['mailonay'],
				'sifre' => sanitize_user($_POST['sifre']),
				'katilimonay' => sanitize_user($_POST['katilimonay']),
				'adres' => $_POST['adres'])	
	);
	
$post = file_get_contents('http://srv-tespoapp/TespoRestFull/api/kuponyolla',null,stream_context_create(array(
    'http' => array(
        'protocol_version' => 1.1,
        'user_agent'       => 'Cekilis',
        'method'           => 'GET',
        'header'           => "Content-type: application/json\r\n".
                              "Connection: close\r\n" .
                              "Content-length: " . strlen($json_data) . "\r\n",
        'content'          => $json_data,
    ),
)));
$json = json_decode($post);	
	//die();
	
//	$file = file_get_contents('http://srv-tespoapp/TespoRestFull/api/kuponyolla/'.$telno.'/'.$sifre.'/'.$ad.'/'.$soyad.'/'.$belgeno.'/'.$belgetrh.'/'.$il.'/'.$ilce.'/'.$telonay.'/'.$mailonay.'/'.$mail);
//	$json = json_decode($file);
	//if($json->Message!='')return $json->Code. ' ' .cv $json->Message;
		
		
		$wpdb->insert('cekilis', 
			array(
				'belgeno' => sanitize_user($_POST['belgeno']),
				'belgetrh' => sanitize_user($_POST['belgetrh']),
				'ad' => sanitize_user($_POST['ad']),
				'soyad' => sanitize_user($_POST['soyad']),
				'il' => sanitize_user($_POST['iltext']),
				'ilce' => sanitize_user($_POST['ilcetext']),
				'telno' => sanitize_user($_POST['telno']),
				'telonay' => sanitize_user($_POST['telonay']),
				'mail' => sanitize_user($_POST['mail']),
				'mailonay' => sanitize_user($_POST['mailonay']),
				'message' => $json->Message,
				'description' => $json->Description,
				'transactionid' => $json->Id,
				'code' => $json->Code,
				'mailonay' => sanitize_user($_POST['mailonay']),
				'sifre' => sanitize_user($_POST['sifre']),
				'katilimonay' => sanitize_user($_POST['katilimonay']),
				'adres' => sanitize_user($_POST['adres'])));
//	}
		$ekle='<p style="color:red;">'.$json->Message.'</p>';
	}
}
}
	
	return '	
	<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>	
	<form autocomplete="off" name="tespo_cekilis" id="tespo_cekilis" method="post" action="" class="earsivbtn">
	<input type="hidden" name="iltext" id="iltext" ' . (isset($_POST['iltext']) ? ' . value="' .  $_POST['iltext'] . '"' : '') . '> 
	<input type="hidden" name="ilcetext" id="ilcetext" ' . (isset($_POST['ilcetext']) ? ' . value="' .  $_POST['ilcetext'] . '"' : '') . '>'.$ekle.'	
		<table>		
			<tr>
				<td style="vertical-align: middle" width="32%">	
					<label style="float: left" for="belgeno">Fatura No</label> 
				</td>
				<td>
					<input type="tel" maxlength="7" required name="belgeno" id="belgeno">
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="belgetrh">Fatura Tarihi</label>
				</td>
				<td>
					<input type="tel"  data-inputmask="\'alias\': \'date\'"  required name="belgetrh" id="belgetrh">
				</td>
			</tr>			
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="ad">Ad</label>
				</td>
				<td>
					<input type="text" minlength="1" required name="ad" pattern="^[A-Za-zÇçŞşĞğÜüÖöıİ]*" title= "Girilen Ad Fiş/Fatura üzerindeki Ad bilgisiyle aynı olmalıdır." id="ad" ' . (isset($_POST['ad']) ? ' . value="' .  $_POST['ad'] . '"' : '') . '>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="soyad">Soyad</label>
				</td>
				<td>	
					<input type="text" minlength="1" required pattern="^[A-Za-zÇçŞşĞğÜüÖöıİ]*" name="soyad" title= "Girilen Soyad Fiş/Fatura üzerindeki Soyad bilgisiyle aynı olmalıdır." id="soyad" ' . (isset($_POST['soyad']) ? ' . value="' .  $_POST['soyad'] . '"' : '') . '>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="adres">Adres</label>
				</td>
				<td>
					<textarea name="adres"  required>' . (isset($_POST['adres']) ? $_POST['adres'] : '') . '</textarea>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">	
					<label style="float: left" for="il">İl</label>
				</td>
				<td>	
					<select id="il" required name="il" >
						<option>İl Seçin</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
						<label style="float: left" for="ilce">İlçe</label>
				</td>	
				<td>
					<select id="ilce" name="ilce" required></select>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="telno">Telefon No</label>
				</td>	
				<td>
					<input type="tel" minlength="5" required name="telno" id="telno" ' . (isset($_POST['telno']) ? ' . value="' .  $_POST['telno'] . '"' : '') . '> 
					<div class="a1"><input type="checkbox" name="telonay" value="telonay" checked>Tespodan SMS almayı kabul ediyorum.</div> 
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="mail">E-Posta Adresi</label>
				</td>
				<td>	
					<input type="email" minlength="5" required name="mail" id="mail" ' . (isset($_POST['mail']) ? ' value="' .  $_POST['mail'] . '"' : '') . '> 
					<div class="a1"><input type="checkbox" name="mailonay" value="mailonay" checked>Tespodan e-posta almayı kabul ediyorum.</div> 
				</td>
			</tr>
			<tr>
				<td style="vertical-align: middle">
					<label style="float: left" for="sifre">Şifre</label>
				</td>	
				<td>
					<input type="text" maxlength="10" required title="Çekiliş kartınızın arkasında yazan üstü kapalı 10 haneli şifreyi giriniz. Aynı anda birden çok kart ile giriş yapmak isterseniz 1 kez formu doldurup başvuru yaptıktan sonra sadece kodu değiştirerek katılım gerçekleştirebilirsiniz." name="sifre" id="sifre" >
				</td>
			</tr>
		</table>
		
		
	
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<div class="g-recaptcha" data-size="normal" data-sitekey="6LdpH4oUAAAAAJjaraxRyOQmyQIF4oUR77DyEGnb"></div>

	<input type="checkbox" name="katilimonay" value="katilimonay" required ' . (isset($_POST['katilimonay']) ? ' checked' : '') . '>&nbsp;<a href="https://tespo.com.tr/tespo-cekilis-2019-yasal-metin/">Katılım Koşullarını okudum ve kabul ediyorum.</a><br></br>
	<input type="submit" name="submit" value="Gönder">
	
	
	</form>	
	<script type="text/javascript">
		var chosen_il_id = ' . (isset($_POST['il']) ? '\'' . $_POST['il'] . '\'' : '0') . '
		var chosen_ilce_id = ' . (isset($_POST['ilce']) ? '\'' . $_POST['ilce'] . '\'' : '0') . '
		$(":input").inputmask();
        $("#telno").inputmask({"mask": "(999)9999999"});
	</script>';
}

?>