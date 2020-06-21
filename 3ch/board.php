<?php

$db_host = 'localhost';
$db_name = 'board_db';
$db_user = 'board_user';
$db_pass = 'board_pass';

//date_default_timezone_set('Asia/Tokyo');
//$now_date = null;

// データベースへ接続する
$link = mysqli_connect( $db_host, $db_user, $db_pass, $db_name );
if ( $link !== false ) {

    $msg     = '';
    $err_msg = '';

    if ( isset( $_POST['send'] ) === true ) {

        $name     = $_POST['name']   ;
        $comment = $_POST['comment'];

        if ( $name !== '' && $comment !== '' ) {

            $query = " INSERT INTO board ( "
                   . "    name , "
                   . "    comment "
                   . " ) VALUES ( "
                   . "'" . mysqli_real_escape_string( $link, $name ) ."', "
                   . "'" . mysqli_real_escape_string( $link, $comment ) . "'"
                   ." ) ";

            $res   = mysqli_query( $link, $query );

            if ( $res !== false ) {
                $msg = '書き込みに成功しました';
            }else{
                $err_msg = '書き込みに失敗しました';
            }
        }else{
            $err_msg = '名前とコメントを記入してください';
        }
    }

    $query  = "SELECT id, name, comment FROM board";
    $res    = mysqli_query( $link,$query );
    $data = array();
    while( $row = mysqli_fetch_assoc( $res ) ) {
        array_push( $data, $row);
    }
    arsort( $data );

} else {
    echo "データベースの接続に失敗しました";
}

// データベースへの接続を閉じる
mysqli_close( $link );
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>ひと言掲示板</title>

		<link href="./style.css" rel="stylesheet">

	</head>
	<body>
		<h1>ひと言掲示板</h1>
        <!-- ここにメッセージの入力フォームを設置 -->
		<?php if( !empty($msg) ):?>
	    	<p class="msg">
			    <?php echo $msg;?>
		    </p>
		<?php endif;?>
		<?php if( !empty($err_msg) ):?>
			<ul class="err_msg">
				<?php foreach((array) $err_msg as $key ):?>
				<li>・
					<?php echo $key;?>
				</li>
				<?php endforeach;?>
			</ul>
		<?php endif;?>
		<form method="post">
			<div>
				<label for="name">表示名</label>
				<input id="name" type="text" name="name" value="">
			</div>
			<div>
				<label for="comment">ひと言メッセージ</label>
				<textarea id="comment" name="comment"></textarea>
			</div>
			<input type="submit" name="send" value="書き込む">
		</form>
		<hr>

		<section>
        <!-- ここに投稿されたメッセージを表示 -->
		<?php if( !empty($data) ):?>
		<?php foreach( $data as $key ): ?>
			<article>
    			<div class="info">
       				<h2>
       					<?php  echo $key['name']; ?>
       				</h2>

   				</div>
   				<p><?php echo $key['comment']; ?></p>
   			</article>
			<?php  endforeach; ?>
			<?php  endif; ?>
		</section>

	</body>
</html>
