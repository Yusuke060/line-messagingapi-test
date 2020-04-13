<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

// イベント種別（今回は2種類のみ）
// message（メッセージが送信されると発生）
// postback（ポストバックオプションに返事されると送信）
$type = $json_obj->{"events"}[0]->{"type"};

$obj = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($obj != "text"){
	exit;
}

//返信データ作成
if ($text == 'はい') {
  $response_format_text = array(
    "type" => "template",
    "altText" => "こちらの〇〇はいかがですか？",
    "template" => array(
      "type" => "buttons",
      "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img1.jpg",
      "title" => "○○レストラン",
      "text" => "お探しのレストランはこれですね",
      "actions" => array(
          array(
            "type" => "postback",
            "label" => "予約する",
            "data" => "action=buy&itemid=123"
          ),
          array(
            "type" => "postback",
            "label" => "電話する",
            "data" => "action=pcall&itemid=123"
          ),
          array(
            "type" => "uri",
            "label" => "詳しく見る",
            "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
          ),
          array(
            "type" => "message",
            "label" => "違うやつ",
            "text" => "違うやつお願い"
          )
      )
    )
  );
} else if ($text == 'いいえ') {
  exit;
} else if ($text == '違うやつお願い') {
  $response_format_text = array(
    "type" => "template",
    "altText" => "候補を３つご案内しています。",
    "template" => array(
      "type" => "carousel",
      "columns" => array(
          array(
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-1.jpg",
            "title" => "●●レストラン",
            "text" => "こちらにしますか？",
            "actions" => array(
              array(
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=111"
              ),
              array(
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=111"
              ),
              array(
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              )
            )
          ),
          array(
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-2.jpg",
            "title" => "▲▲レストラン",
            "text" => "それともこちら？（２つ目）",
            "actions" => array(
              array(
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=222"
              ),
              array(
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=222"
              ),
              array(
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              )
            )
          ),
          array(
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-3.jpg",
            "title" => "■■レストラン",
            "text" => "はたまたこちら？（３つ目）",
            "actions" => array(
              array(
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=333"
              ),
              array(
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=333"
              ),
              array(
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              )
            )
          )
      )
    )
  );
} else if ($text == 'おはよう') {
  $response_format_text = array(
    "type" => "text",
    "text" => "【'.$text.'】とは何ですか？"
  );
} else if ($text == '昼ごはん') {
  $response_format_text = array(
    'type' => 'text',
    'text' => '【'.$text.'】とは何ですか？'
  );
} else if ($text == 'スタンプ') {
  $response_format_text = array(
    "type"      => "sticker",
    "packageId" => 1,
    "stickerId" => 1
  );
} else if ($text == '写真') {
  $response_format_text = array(
    'type'      => 'image',
    'packageId' => "https://" . $_SERVER['SERVER_NAME'] . "/img2-3.jpg",
    'stickerId' => "https://" . $_SERVER['SERVER_NAME'] . "/img2-3.jpg"
  );
} else if ($text == 'sticker') {
  $response_format_text = array(
    'type'      => 'sticker',
    'packageId' => 1,
    'stickerId' => 1
  );
} else {
  $response_format_text = array(
    "type" => "template",
    "altText" => "こんにちわ 何かご用ですか？（はい／いいえ）",
    "template" => array(
        "type" => "confirm",
        "text" => "こんにちわ 何かご用ですか？",
        "actions" => array(
            array(
              "type" => "message",
              "label" => "はい",
              "text" => "はい"
            ),
            array(
              "type" => "message",
              "label" => "いいえ",
              "text" => "いいえ"
            )
        )
    )
  );
}

$post_data = array(
	"replyToken" => $replyToken,
	"messages" => array($response_format_text)
);

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
