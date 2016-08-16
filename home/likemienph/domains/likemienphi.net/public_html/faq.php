<?php
$titleweb =  'Hướng dẫn, Tăng like Facebook, tang like facebook';
include('header.php');?>
<style type="text/css"> a.FAQ{background:#efefef;color:#00386b;font-size:11px;font-family:Verdana;margin-bottom:5px;width:637px;padding:10px;width:637px;border:1px solid #ddd;border-radius:4px;display:block;text-decoration:none;}a.FAQ:hover{background:#ddd;}a.FAQ span{font-weight:bold;}a.FAQ div{display:none;padding-top:10px;font-size:12px;color:#777;} </style>
<script type="text/javascript"> $('.FAQ').live('click', function(e){ e.preventDefault(); $item = $(this).find('div'); $item.slideToggle('fast'); }); </script>
<div class="content t-left">
<h2 class="title"><?=$lang['b_06']?></h2>
<?
$sql = $db->Query("SELECT question,answer FROM `faq` ORDER BY id ASC");
if($db->GetNumRows($sql) == 0){
	echo '<div class="msg"><div class="info">'.$lang['b_250'].'</div></div>';
}
$j = 0;
foreach($db->FetchArrayAll($sql) as $faq){
	$j++;
	echo '<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>'.$j.') '.$faq['question'].'</span><div>'.BBCode(nl2br($faq['answer'])).'</div></a>';
}
?>


<style type="text/css"> a.FAQ{background:#efefef;color:#00386b;font-size:11px;font-family:Verdana;margin-bottom:5px;width:637px;padding:10px;border:1px solid #ddd;border-radius:4px;display:block;text-decoration:none;}a.FAQ:hover{background:#ddd;}a.FAQ span{font-weight:bold;}a.FAQ div{display:none;padding-top:10px;font-size:12px;color:#777;} </style>
<h2 class="title">Hỏi đáp</h2>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>1) Website này hoạt động như thế nào ?</span><div>Website của chúng tôi là hệ thống giúp tăng miễn phí "Like", "+1", "Follower", "View" và lượng truy cập web cho các trang Facebook fanpage, Google+, Twitter, Digg, Youtube video, và website của bạn . Nền tảng trao đổi của chúng tôi dựa trên nguyên tắc bạn phải thao tác "Like", "+1", "Follower", "View" và tự động duyệt web những trang của người khác trước để kiếm Coins sau đó người khác mới có thể thao tác tương tự với trang của bạn.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>2) Coins là gì ? Tiền mặt là gì ?</span><div>Coins là điểm bạn nhận được sau những thao tác "Like", "+1", "Follower", "View" và tự động duyệt web trên website hoặc từ hoạt động mua Xu trực tiếp. (xem đường dẫn "Mua xu" tại cột bên trái để biết thêm chi tiết)</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>3) Với coins tôi có thể làm được việc gì ?</span><div>Sau khi đã có coins, bạn phải thêm các "trang" của mình vào hệ thống (xem đường dẫn "Thêm trang" tại cột bên trái). Sau khi đã thêm trang thành công, bạn click vào "Trang của tôi" ở bên cột trái để xem, chỉnh sửa, và xóa trang. Với mỗi thao tác đó bạn sẽ mất đi số Xu tương ứng với CPC đã đặt ban đầu cho trang của mình.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>4) Hệ thống sẽ hiển thị các trang để người dùng thao tác theo thứ tự nào?</span><div>Các trang được đặt mức coins CPC càng cao càng xuất hiện trên cùng một cách ngẫu nhiên, theo thứ tự giảm dần của CPC.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>5) Tại sao các trang của tôi không được tăng Like, Follow, View,...? Tại sao tôi không thể Like, Follow, View,... trang của người khác?</span><div>Nguyên nhân có thể là: bạn không click tăng cho người khác loại page mà bạn muốn tăng; người dùng đã không thêm trang hợp lệ (địa chỉ các đường dẫn fan page, URL, ảnh trên Facebook, hoặc các đường dẫn video Youtube, URL trang web không hợp lệ... ); các trang bị lỗi không xác định; kết nối tới các MXH gián đoạn tạm thời; ngoài ra nếu bạn đã hết Xu thì người khác cũng không thể thao tác đối với các trang của bạn.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>6) Tôi có thể thêm nhiều trang chứ?</span><div>Bạn có thể thêm trang không giới hạn. Tuy nhiên, bạn phải đảm bảo còn Xu trong tài khoản để được người khác thao tác.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>7) Vì sao tôi không thể thêm Facebook Followers ( facebook theo dõi ) ?</span><div>Tài khoản của bạn chưa bật chức năng ngày ! cần có 1 người theo dõi trước thì hệ thống mới hoạt động ! Mở tính năng này trong phần : cài đặt /người dùng theo dõi ( trong tài khoản facebook của bạn) !</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>8) Hệ thống google +1 ?</span><div>Nhận thấy hệ thống google +1 hoạt động không hiệu quả do phía google không chấp nhận like giới thiệu từ web site khác .

Trong thời gian nghiên cứu thêm cách thức khác mua bán like sẽ tắt hệ thống này để đảm bảo an toàn không ảnh hưởng tới thành viên khi tham gia http://ttovn.com</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>9) Tôi không thể add page do lỗi page đã tồn tại trên hệ thống</span><div>Mỗi page chỉ được add trên hệ thống 1 lần để tránh trùng lặp.

Nếu bạn đã có tài khoản hãy sử dụng mục lấy lại mật khẩu ở website 

Trường hợp khác hãy trình bầy trong mục liên lạc với chúng tôi ở cuối website 

Chúng tôi sẽ hỗ trợ bạn !</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>10) Truy cập VIP để làm gì?</span><div>Truy cập VIP giúp bạn có thể nâng mức CPC lên 16 Xu, giúp trang của bạn được xếp phía trên để người khác có thể dễ dàng thao tác với trang bạn trước nhất. Vui lòng click vào đường dẫn "Nâng cấp truy cập VIP" bên cột trái để biết thêm chi tiết về các gói VIP ưu đãi.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>11) Hệ thống sẽ hiển thị các trang để người dùng thao tác theo thứ tự nào?</span><div>Các trang được đặt mức coins CPC càng cao càng xuất hiện trên cùng một cách ngẫu nhiên, theo thứ tự giảm dần của CPC.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>12) Tại sao các trang của tôi không được tăng Like, Follow, View,...? Tại sao tôi không thể Like, Follow, View,... trang của người khác?</span><div>Nguyên nhân có thể là: bạn không click tăng cho người khác loại page mà bạn muốn tăng; người dùng đã không thêm trang hợp lệ (địa chỉ các đường dẫn fan page, URL, ảnh trên Facebook, hoặc các đường dẫn video Youtube, URL trang web không hợp lệ... ); các trang bị lỗi không xác định; kết nối tới các MXH gián đoạn tạm thời; ngoài ra nếu bạn đã hết Xu thì người khác cũng không thể thao tác đối với các trang của bạn.</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>13) Tôi có thể thêm nhiều trang chứ?</span><div>Bạn có thể thêm trang không giới hạn. Tuy nhiên, bạn phải đảm bảo còn Xu trong tài khoản để được người khác thao tác. !</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>14) Tóm lại, để tăng Like, +1, Follower, View... và lượng truy cập web tôi phải thực hiện những bước nào?</span><div>a. Đăng nhập.

b. Thêm trang của bạn (với Twitter, Youtube subcriber và Digg bạn phải thêm tài khoản của chính bạn, xem đường dẫn cột bên phải).

c. Thực hiện các thao tác "Like", "Follow" "+1", "view", "lướt web tự động"... với các trang của người khác để kiếm Xu.

d. Lặp lại bất cứ lúc nào bạn có thể.
 !</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>15) Tôi sẽ phải Like Facebook page để được Like lại hay tôi chỉ cần có điểm là có thể được Like?</span><div>Bạn phải Like Facebook page để Facebook page của bạn được Like trở lại, tương tự với các tính năng khác. Hệ thống cho phép sử dụng điểm chung để Like, Follow, View chéo giữa các tính năng nhưng sẽ không ưu tiên hiển thị trang của bạn ở trên. Trừ khi bạn là thành viên VIP. !</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>16) Tại sao tôi đã Like một số trang mà hệ thống báo là Facebook, Twitter, Youtube, hay Google chưa xác nhận?</span><div> Hãy nhấn nút BỎ QUA một số ít trang có lỗi này và thao tác trên những trang mới không có lỗi. Có rất nhiều lý do khách quan khiến một số trang bị lỗi dạng này và không thể loại bỏ. Chúng tôi không muốn các bạn mất thời gian vào việc tìm hiểu những nguyên nhân vô nghĩa này.!</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>17) Tôi phải đăng nhập đúng tài khoản Youtube và Twitter để có thể thao tác và nhận điểm?</span><div> Đúng. Riêng đối với 2 mạng xã hội này bạn phải đăng nhập và sử dụng đúng tài khoản đã đăng ký trên hệ thống của chúng tôi đê thao tác và nhận điểm. Nếu không, hệ thống sẽ báo lỗi không xác nhận kể cả khi bạn đã thực hiện thành công các thao tác.!</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>18) Tôi đặt CPC 6 thì người click sẽ nhận được bao nhiêu Coins và tôi mất bao nhiêu Xu mỗi lượt click?</span><div> Bạn sẽ mất số Xu đúng bằng CPC còn người click sẽ chỉ nhận được số coins tương đương CPC-1, tức là 5 coins</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>17) Trang web này có bán Like không?</span><div> Không. 100%!</div></a>
<a class="FAQ" href="javascript:void(0)" style="width: 637px;"><span>17) Tôi có thể xóa page trên tài khoản cũ và thêm vào tài khoản mới ?</span><div> Không ! Điều này gây trùng lặp khó khăn cho người sử dụng 
Những page vi phạm sẽ bị ban ngay lập tức !</div></a></div>



</div>
<div style="margin-left:auto; margin-right:auto; text-align:center; padding-left:180px;">
<?include('footer.php');?> </div>  