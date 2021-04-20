<?php
session_start();

if(isset($_SESSION['SessionId'])) {
    include("bd.php");
	$GUID=$_SESSION['SessionId'];
    $res=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");
    $user_data=mysql_fetch_array($res);
    $group=$user_data['GroupId'];
	
	if ($group == 1) {
		header("location: freelance-workspace/index.php");
     } else {
	 	header("location: employer-workspace/index.php");
	 }
}
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<html>

<head>

<link href="styles_index.css" rel="stylesheet" type="text/css" />

</head>

<body>

<header  class="GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
logo and Need help? contact us.
</div>
</div>
</header>

<section class="Signup-freelancer">
<div class="SUF_main">
<div class="SUF_submain">
<div class="SUF_tittle">
Create an account
</div>
<form action="signup-complete.php" name="signup" id="signup" class="SUF_form" method="post">
<div class="SUF_signforms">
<div class="Signup_first_name" id="Signup_FirstName">
<input type="text" name="FirstName" id="FirstName" placeholder="Имя" maxlength=32 class="Signforms">
</div>
<div class="Signup_last_name" id="Signup_LastName">
<input type="text" name="LastName" id="LastName" placeholder="Фамилия" maxlength=32 class="Signforms">
</div>
<div class="Signup_username" id="Signup_UserName">
<input type="text" name="Username" id="Username" placeholder="Логин" maxlength=32 class="Signforms">
</div>
<div class="Signup_email" id="Signup_Email">
<input type="text" name="Email" id="Email" placeholder="Email" maxlength=32 class="Signforms">
</div>
<div class="Signup_country" id="Signup_Country">
 <select name="Country" class="" id="Country">
<option value="" label="Country">Country</option>
<option value="1" label="Albania" >
                    Albania                </option>
                            <option value="2" label="Algeria" >
                    Algeria                </option>
                            <option value="3" label="American Samoa" >
                    American Samoa                </option>
                            <option value="4" label="Andorra" >
                    Andorra                </option>
                            <option value="5" label="Angola" >
                    Angola                </option>
                            <option value="6" label="Anguilla" >
                    Anguilla                </option>
                            <option value="7" label="Antarctica" >
                    Antarctica                </option>
                            <option value="8" label="Antigua and Barbuda" >
                    Antigua and Barbuda                </option>
                            <option value="9" label="Argentina" >
                    Argentina                </option>
                            <option value="10" label="Armenia" >
                    Armenia                </option>
                            <option value="11" label="Aruba" >
                    Aruba                </option>
                            <option value="12" label="Australia" >
                    Australia                </option>
                            <option value="13" label="Austria" >
                    Austria                </option>
                            <option value="14" label="Azerbaijan" >
                    Azerbaijan                </option>
                            <option value="15" label="Bahamas" >
                    Bahamas                </option>
                            <option value="16" label="Bahrain" >
                    Bahrain                </option>
                            <option value="17" label="Bangladesh" >
                    Bangladesh                </option>
                            <option value="18" label="Barbados" >
                    Barbados                </option>
                            <option value="19" label="Belarus" >
                    Belarus                </option>
                            <option value="20" label="Belgium" >
                    Belgium                </option>
                            <option value="21" label="Belize" >
                    Belize                </option>
                            <option value="22" label="Benin" >
                    Benin                </option>
                            <option value="23" label="Bermuda" >
                    Bermuda                </option>
                            <option value="24" label="Bhutan" >
                    Bhutan                </option>
                            <option value="25" label="Bolivia" >
                    Bolivia                </option>
                            <option value="26" label="Bosnia and Herzegovina" >
                    Bosnia and Herzegovina                </option>
                            <option value="27" label="Botswana" >
                    Botswana                </option>
                            <option value="28" label="Bouvet Island" >
                    Bouvet Island                </option>
                            <option value="29" label="Brazil" >
                    Brazil                </option>
                            <option value="30" label="British Indian Ocean Territory" >
                    British Indian Ocean Territory                </option>
                            <option value="31" label="British Virgin Islands" >
                    British Virgin Islands                </option>
                            <option value="32" label="Brunei Darussalam" >
                    Brunei Darussalam                </option>
                            <option value="33" label="Bulgaria" >
                    Bulgaria                </option>
                            <option value="34" label="Burkina Faso" >
                    Burkina Faso                </option>
                            <option value="35" label="Burundi" >
                    Burundi                </option>
                            <option value="36" label="Cambodia" >
                    Cambodia                </option>
                            <option value="37" label="Cameroon" >
                    Cameroon                </option>
                            <option value="38" label="Canada" >
                    Canada                </option>
                            <option value="39" label="Cape Verde" >
                    Cape Verde                </option>
                            <option value="40" label="Cayman Islands" >
                    Cayman Islands                </option>
                            <option value="41" label="Central African Republic" >
                    Central African Republic                </option>
                            <option value="42" label="Chad" >
                    Chad                </option>
                            <option value="43" label="Chile" >
                    Chile                </option>
                            <option value="44" label="China" >
                    China                </option>
                            <option value="45" label="Christmas Island" >
                    Christmas Island                </option>
                            <option value="46" label="Cocos (Keeling) Islands" >
                    Cocos (Keeling) Islands                </option>
                            <option value="47" label="Colombia" >
                    Colombia                </option>
                            <option value="48" label="Comoros" >
                    Comoros                </option>
                            <option value="49" label="Congo" >
                    Congo                </option>
                            <option value="50" label="Congo, the Democratic Republic of the" >
                    Congo, the Democratic Republic of the                </option>
                            <option value="51" label="Cook Islands" >
                    Cook Islands                </option>
                            <option value="52" label="Costa Rica" >
                    Costa Rica                </option>
                            <option value="53" label="Cote d'Ivoire" >
                    Cote d'Ivoire                </option>
                            <option value="54" label="Croatia" >
                    Croatia                </option>
                            <option value="55" label="Cyprus" >
                    Cyprus                </option>
                            <option value="56" label="Czech Republic" >
                    Czech Republic                </option>
                            <option value="57" label="Denmark" >
                    Denmark                </option>
                            <option value="58" label="Djibouti" >
                    Djibouti                </option>
                            <option value="59" label="Dominica" >
                    Dominica                </option>
                            <option value="60" label="Dominican Republic" >
                    Dominican Republic                </option>
                            <option value="61" label="Ecuador" >
                    Ecuador                </option>
                            <option value="62" label="Egypt" >
                    Egypt                </option>
                            <option value="63" label="El Salvador" >
                    El Salvador                </option>
                            <option value="64" label="Equatorial Guinea" >
                    Equatorial Guinea                </option>
                            <option value="65" label="Eritrea" >
                    Eritrea                </option>
                            <option value="66" label="Estonia" >
                    Estonia                </option>
                            <option value="67" label="Ethiopia" >
                    Ethiopia                </option>
                            <option value="68" label="Falkland Islands" >
                    Falkland Islands                </option>
                            <option value="69" label="Faroe Islands" >
                    Faroe Islands                </option>
                            <option value="70" label="Fiji" >
                    Fiji                </option>
                            <option value="71" label="Finland" >
                    Finland                </option>
                            <option value="72" label="France" >
                    France                </option>
                            <option value="73" label="French Guiana" >
                    French Guiana                </option>
                            <option value="74" label="French Polynesia" >
                    French Polynesia                </option>
                            <option value="75" label="French Southern and Antarctic Lands" >
                    French Southern and Antarctic Lands                </option>
                            <option value="76" label="Gabon" >
                    Gabon                </option>
                            <option value="77" label="Gambia" >
                    Gambia                </option>
                            <option value="78" label="Georgia" >
                    Georgia                </option>
                            <option value="79" label="Germany" >
                    Germany                </option>
                            <option value="80" label="Ghana" >
                    Ghana                </option>
                            <option value="81" label="Gibraltar" >
                    Gibraltar                </option>
                            <option value="82" label="Greece" >
                    Greece                </option>
                            <option value="83" label="Greenland" >
                    Greenland                </option>
                            <option value="84" label="Grenada" >
                    Grenada                </option>
                            <option value="85" label="Guadeloupe" >
                    Guadeloupe                </option>
                            <option value="86" label="Guam" >
                    Guam                </option>
                            <option value="87" label="Guatemala" >
                    Guatemala                </option>
                            <option value="88" label="Guinea" >
                    Guinea                </option>
                            <option value="89" label="Guinea-Bissau" >
                    Guinea-Bissau                </option>
                            <option value="90" label="Guyana" >
                    Guyana                </option>
                            <option value="91" label="Haiti" >
                    Haiti                </option>
                            <option value="92" label="Heard Island and McDonald Islands" >
                    Heard Island and McDonald Islands                </option>
                            <option value="93" label="Holy See" >
                    Holy See                </option>
                            <option value="94" label="Honduras" >
                    Honduras                </option>
                            <option value="95" label="Hong Kong" >
                    Hong Kong                </option>
                            <option value="96" label="Hungary" >
                    Hungary                </option>
                            <option value="97" label="Iceland" >
                    Iceland                </option>
                            <option value="98" label="India" >
                    India                </option>
                            <option value="99" label="Indonesia" >
                    Indonesia                </option>
                            <option value="100" label="Ireland" >
                    Ireland                </option>
                            <option value="101" label="Isle of Man" >
                    Isle of Man                </option>
                            <option value="102" label="Israel" >
                    Israel                </option>
                            <option value="103" label="Italy" >
                    Italy                </option>
                            <option value="104" label="Jamaica" >
                    Jamaica                </option>
                            <option value="105" label="Japan" >
                    Japan                </option>
                            <option value="106" label="Jordan" >
                    Jordan                </option>
                            <option value="107" label="Kazakhstan" >
                    Kazakhstan                </option>
                            <option value="108" label="Kenya" >
                    Kenya                </option>
                            <option value="109" label="Kiribati" >
                    Kiribati                </option>
                            <option value="110" label="Kuwait" >
                    Kuwait                </option>
                            <option value="111" label="Kyrgyzstan" >
                    Kyrgyzstan                </option>
                            <option value="112" label="Laos" >
                    Laos                </option>
                            <option value="113" label="Latvia" >
                    Latvia                </option>
                            <option value="114" label="Lebanon" >
                    Lebanon                </option>
                            <option value="115" label="Lesotho" >
                    Lesotho                </option>
                            <option value="116" label="Liechtenstein" >
                    Liechtenstein                </option>
                            <option value="117" label="Lithuania" >
                    Lithuania                </option>
                            <option value="118" label="Luxembourg" >
                    Luxembourg                </option>
                            <option value="119" label="Macao" >
                    Macao                </option>
                            <option value="120" label="Macedonia" >
                    Macedonia                </option>
                            <option value="121" label="Madagascar" >
                    Madagascar                </option>
                            <option value="122" label="Malawi" >
                    Malawi                </option>
                            <option value="123" label="Malaysia" >
                    Malaysia                </option>
                            <option value="124" label="Maldives" >
                    Maldives                </option>
                            <option value="125" label="Mali" >
                    Mali                </option>
                            <option value="126" label="Malta" >
                    Malta                </option>
                            <option value="127" label="Marshall Islands" >
                    Marshall Islands                </option>
                            <option value="128" label="Martinique" >
                    Martinique                </option>
                            <option value="129" label="Mauritania" >
                    Mauritania                </option>
                            <option value="130" label="Mauritius" >
                    Mauritius                </option>
                            <option value="131" label="Mayotte" >
                    Mayotte                </option>
                            <option value="132" label="Mexico" >
                    Mexico                </option>
                            <option value="133" label="Micronesia, Federated States of" >
                    Micronesia, Federated States of                </option>
                            <option value="134" label="Moldova" >
                    Moldova                </option>
                            <option value="135" label="Monaco" >
                    Monaco                </option>
                            <option value="136" label="Mongolia" >
                    Mongolia                </option>
                            <option value="137" label="Montenegro" >
                    Montenegro                </option>
                            <option value="138" label="Montserrat" >
                    Montserrat                </option>
                            <option value="139" label="Morocco" >
                    Morocco                </option>
                            <option value="140" label="Mozambique" >
                    Mozambique                </option>
                            <option value="141" label="Myanmar" >
                    Myanmar                </option>
                            <option value="142" label="Namibia" >
                    Namibia                </option>
                            <option value="143" label="Nepal" >
                    Nepal                </option>
                            <option value="144" label="Netherlands" >
                    Netherlands                </option>
                            <option value="145" label="Netherlands Antilles" >
                    Netherlands Antilles                </option>
                            <option value="146" label="New Caledonia" >
                    New Caledonia                </option>
                            <option value="147" label="New Zealand" >
                    New Zealand                </option>
                            <option value="148" label="Nicaragua" >
                    Nicaragua                </option>
                            <option value="149" label="Niger" >
                    Niger                </option>
                            <option value="150" label="Nigeria" >
                    Nigeria                </option>
                            <option value="151" label="Niue" >
                    Niue                </option>
                            <option value="152" label="Norfolk Island" >
                    Norfolk Island                </option>
                            <option value="153" label="Northern Mariana Islands" >
                    Northern Mariana Islands                </option>
                            <option value="154" label="Norway" >
                    Norway                </option>
                            <option value="155" label="Oman" >
                    Oman                </option>
                            <option value="156" label="Pakistan" >
                    Pakistan                </option>
                            <option value="157" label="Palau" >
                    Palau                </option>
                            <option value="158" label="Palestinian Territories" >
                    Palestinian Territories                </option>
                            <option value="159" label="Panama" >
                    Panama                </option>
                            <option value="160" label="Papua New Guinea" >
                    Papua New Guinea                </option>
                            <option value="161" label="Paraguay" >
                    Paraguay                </option>
                            <option value="162" label="Peru" >
                    Peru                </option>
                            <option value="163" label="Philippines" >
                    Philippines                </option>
                            <option value="164" label="Pitcairn" >
                    Pitcairn                </option>
                            <option value="165" label="Poland" >
                    Poland                </option>
                            <option value="166" label="Portugal" >
                    Portugal                </option>
                            <option value="167" label="Puerto Rico" >
                    Puerto Rico                </option>
                            <option value="168" label="Qatar" >
                    Qatar                </option>
                            <option value="169" label="Reunion" >
                    Reunion                </option>
                            <option value="170" label="Romania" >
                    Romania                </option>
                            <option value="171" label="Russia" >
                    Russia                </option>
                            <option value="172" label="Rwanda" >
                    Rwanda                </option>
                            <option value="173" label="Saint Helena" >
                    Saint Helena                </option>
                            <option value="174" label="Saint Kitts and Nevis" >
                    Saint Kitts and Nevis                </option>
                            <option value="175" label="Saint Lucia" >
                    Saint Lucia                </option>
                            <option value="176" label="Saint Pierre and Miquelon" >
                    Saint Pierre and Miquelon                </option>
                            <option value="177" label="Saint Vincent and the Grenadines" >
                    Saint Vincent and the Grenadines                </option>
                            <option value="178" label="Samoa" >
                    Samoa                </option>
                            <option value="179" label="San Marino" >
                    San Marino                </option>
                            <option value="180" label="Sao Tome and Principe" >
                    Sao Tome and Principe                </option>
                            <option value="181" label="Saudi Arabia" >
                    Saudi Arabia                </option>
                            <option value="182" label="Senegal" >
                    Senegal                </option>
                            <option value="183" label="Serbia" >
                    Serbia                </option>
                            <option value="184" label="Seychelles" >
                    Seychelles                </option>
                            <option value="185" label="Sierra Leone" >
                    Sierra Leone                </option>
                            <option value="186" label="Singapore" >
                    Singapore                </option>
                            <option value="187" label="Slovakia" >
                    Slovakia                </option>
                            <option value="188" label="Slovenia" >
                    Slovenia                </option>
                            <option value="189" label="Solomon Islands" >
                    Solomon Islands                </option>
                            <option value="190" label="Somalia" >
                    Somalia                </option>
                            <option value="191" label="South Africa" >
                    South Africa                </option>
                            <option value="192" label="South Korea" >
                    South Korea                </option>
                            <option value="193" label="Spain" >
                    Spain                </option>
                            <option value="194" label="Sri Lanka" >
                    Sri Lanka                </option>
                            <option value="195" label="Suriname" >
                    Suriname                </option>
                            <option value="196" label="Svalbard and Jan Mayen" >
                    Svalbard and Jan Mayen                </option>
                            <option value="197" label="Swaziland" >
                    Swaziland                </option>
                            <option value="198" label="Sweden" >
                    Sweden                </option>
                            <option value="199" label="Switzerland" >
                    Switzerland                </option>
                            <option value="200" label="Taiwan" >
                    Taiwan                </option>
                            <option value="201" label="Tajikistan" >
                    Tajikistan                </option>
                            <option value="202" label="Tanzania" >
                    Tanzania                </option>
                            <option value="203" label="Thailand" >
                    Thailand                </option>
                            <option value="204" label="Timor-Leste" >
                    Timor-Leste                </option>
                            <option value="205" label="Togo" >
                    Togo                </option>
                            <option value="206" label="Tokelau" >
                    Tokelau                </option>
                            <option value="207" label="Tonga" >
                    Tonga                </option>
                            <option value="208" label="Trinidad and Tobago" >
                    Trinidad and Tobago                </option>
                            <option value="209" label="Tunisia" >
                    Tunisia                </option>
                            <option value="210" label="Turkey" >
                    Turkey                </option>
                            <option value="211" label="Turkmenistan" >
                    Turkmenistan                </option>
                            <option value="212" label="Turks and Caicos Islands" >
                    Turks and Caicos Islands                </option>
                            <option value="213" label="Tuvalu" >
                    Tuvalu                </option>
                            <option value="214" label="Uganda" >
                    Uganda                </option>
                            <option value="215" label="Ukraine" >
                    Ukraine                </option>
                            <option value="216" label="United Arab Emirates" >
                    United Arab Emirates                </option>
                            <option value="217" label="United Kingdom" >
                    United Kingdom                </option>
                            <option value="218" label="United States" >
                    United States                </option>
                            <option value="219" label="United States Minor Outlying Islands" >
                    United States Minor Outlying Islands                </option>
                            <option value="220" label="United States Virgin Islands" >
                    United States Virgin Islands                </option>
                            <option value="221" label="Uruguay" >
                    Uruguay                </option>
                            <option value="222" label="Uzbekistan" >
                    Uzbekistan                </option>
                            <option value="223" label="Vanuatu" >
                    Vanuatu                </option>
                            <option value="224" label="Venezuela" >
                    Venezuela                </option>
                            <option value="225" label="Vietnam" >
                    Vietnam                </option>
                            <option value="226" label="Wallis and Futuna" >
                    Wallis and Futuna                </option>
                            <option value="227" label="Western Sahara" >
                    Western Sahara                </option>
                            <option value="228" label="Yemen" >
                    Yemen                </option>
                            <option value="229" label="Zambia" >
                    Zambia                </option>
                            <option value="230" label="Zimbabwe" >
                    Zimbabwe                </option>
                    </select>
</div>
<div class="Signup_password" id="Signup_Password">
<input type="text" name="Password" id="Password" placeholder="Пароль" maxlength=64 class="Signforms">
</div>
<div class="Signup_password" id="Signup_Password_check">
<input type="text" name="Password_check" id="Password_check" maxlength=64 placeholder="Подтвердите пароль" class="Signforms">
</div>
<div class="Signup_captcha" id="Signup_Captcha">

</div>
<input type="hidden" name="Type" value="1"  >
<button type="submit" id="send" class="Signup_submit">Подтвердить</button>
</div>
</form>
</div>
</div>
</section>

<footer class="GlobalFooter">
<div class="GlobalFooterBot">
<div class="GlovalFooterBot_row">
<div class="GlobalFooterBot-col">
<a class="FooterLogo" href="/">
<img src="" alt="SiteName" width="90" height="25">
</a>
<p class="FooterLegal">© 2014 ServiceName.</p>
</div>
</div>
</div>
</footer>


<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="valid.js" charset="utf-8"></script>
</body>

</html>
