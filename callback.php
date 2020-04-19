<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

    
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// イベント種別（今回は2種類のみ）
// message（メッセージが送信されると発生）
// postback（ポストバックオプションに返事されると送信）
$type = $jsonObj->{"events"}[0]->{"type"};

// メッセージオブジェクト（今回は4種類のみ）
// text（テキストを受け取った時）
// sticker（スタンプを受け取った時）
// image（画像を受け取った時）
// location（位置情報を受け取った時）
$msg_obj = $jsonObj->{"events"}[0]->{"message"}->{"type"};

//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};


if($type == 'message') {
    //メッセージ以外のときは何も返さず終了
    if($msg_obj != "text"){
        exit;
    }

    if($msg_obj == "text"){
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
                'type' => 'text',
                'text' => '【'.$text.'】とは何ですか？'
            );
        } else if ($text == '絵文字') {
           $response_format_text = array(
                'type'   => 'text',
                'text'   => '$ emoji',
                'emojis' => array(
                    array(
                        'index'     => 0,
                        'productId' => '5ac21542031a6752fb806d55',
                        'sticonId'  => '117'
                    )
                )
           );
        } else if ($text == 'クイック') {
            $response_format_text = array(
                'type' => 'text',
                'text' => '下から選んでください。',
                'quickReply' => array(
                    'items' => array(
                        array(
                            'type' => 'action',
                            'action' => array(
                                'type' => 'cameraRoll',
                                'label' => 'Send photo'
                            )
                        ),
                        array(
                            'type' => 'action',
                            'action' => array(
                                'type' => 'camera',
                                'label' => 'Open camera'
                            )
                        )
                    )
                )
            );
        }else if ($text == 'スタンプ') {
            $response_format_text = array(
                'type'      => 'sticker',
                'packageId' => 1,
                'stickerId' => 1
            );
        } else if ($text == '写真') {
            $response_format_text = array(
                'type'      => 'image',
                'originalContentUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/img1.jpg',
                'previewImageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/img2-3.jpg'
            );
        } else if ($text == '動画') {
            $response_format_text = array(
                'type'               => 'video',
                'originalContentUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.mp4',
                'previewImageUrl'    => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg'
            );
        } else if ($text == '音声') {
            $response_format_text = array(
                'type'               => 'audio',
                'originalContentUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/oimtsm.m4a',
                'duration'    => 2000
            );
        } else if ($text == '位置') {
            $response_format_text = array(
                'type'      => 'location',
                'title'     => '皇居',
                'address'   => '東京都千代田区千代田1番1号',
                'latitude'  => 35.677730,
                'longitude' => 139.754813
            );
        } else if ($text == 'ボタン') {
            $response_format_text = array(
                'type'     => 'template',
                'altText'  => 'ボタンテスト',
                'template' => array(
                    'type'    => 'buttons',
                    'thumbnailImageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg',
                    'title'   => 'ボタンタイトル' ,
                    'text'    => 'テキストメッセージ。タイトルがないときは最大160文字、タイトルがあるときは最大60文字',
                    'actions' => array(
                        array(
                            'type'  => 'message',
                            'label' => 'ラベル1',
                            'text'  => 'アクションメッセージ1'
                        ),
                        array(
                            'type'  => 'uri',
                            'uri' => 'https://' . $_SERVER['SERVER_NAME'] . '/',
                            'label'  => 'ホームページ'
                        ),
                        array(
                            'type'  => 'datetimepicker',
                            'label' => '日時',
                            'data'  => 'datetemp',
                            'mode'  => 'date'
                        )
                    )
                )
            );
        } else if ($text == '確認') {
            $response_format_text = array(
                'type'     => 'template',
                'altText'  => '確認テスト',
                'template' => array(
                    'type'    => 'confirm',
                    'text'   => '確認タイトル',
                    'actions' => array(
                        array(
                            'type'  => 'postback',
                            'label' => '参加',
                            'data'  => 'sanka'
                        ),
                        array(
                            'type'  => 'postback',
                            'label' => '不参加',
                            'data'  => 'fusanka'
                        )
                    )
                )
            );
        } else if ($text == 'カルーセル') {
            $response_format_text = array(
                'type'     => 'template',
                'altText'  => 'カルーセルテスト',
                'template' => array(
                    'type'    => 'carousel',
                    'columns' => array(
                        array(
                            'thumbnailImageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg',
                            'title'   => 'カルーセルタイトル1',
                            'text'    => 'タイトルか画像がある場合は最大60文字、どちらもない場合は最大120文字',
                            'actions' => array(
                                array(
                                    'type'  => 'uri',
                                    'uri' => 'https://line.me/R/nv/profile',
                                    'label'  => 'プロフィール'
                                )
                            )
                        ),
                        array(
                            'thumbnailImageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg',
                            'title'   => 'カルーセルタイトル2',
                            'text'    => 'タイトルか画像がある場合は最大60文字、どちらもない場合は最大120文字',
                            'actions' => array(
                                array(
                                    'type' => 'message',
                                    'label' => 'ラベルです',
                                    'text' => 'メッセージ'
                                )
                            )
                        )
                    )
                )
            );
        } else if ($text == '画像カルーセル') {
            $response_format_text = array(
                'type'     => 'template',
                'altText'  => '画像カルーセルテスト',
                'template' => array(
                    'type'    => 'image_carousel',
                    'columns' => array(
                        array(
                            'imageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg',
                            'action' => array(
                                array(
                                    'type' => 'message',
                                    'label' => 'ラベル1',
                                    'text' => 'メッセージ1'
                                )
                            )
                        ),
                        array(
                            'imageUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/kourin.jpg',
                            'action' => array(
                                array(
                                    'type' => 'message',
                                    'label' => 'ラベル2',
                                    'text' => 'メッセージ2'
                                )
                            )
                        )
                    )
                )
            );
        } else if ($text == '昼ごはん') {
            $response_format_text = array(
                'type' => 'text',
                'text' => '何が食べたいですか？'
            );
        } else if ($text == '111') {
            $response_format_text = array(
                'type' => 'text',
                'text' => '【'.$text.'】ですね？'
            );
        } else {
          $response_format_text = array(
            "type" => "template",
            "altText" => "こんにちは 何かご用ですか？（はい／いいえ）",
            "template" => array(
                "type" => "confirm",
                "text" => "こんにちは 何かご用ですか？",
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
    }
}else if($type == 'postback') {
    // 送られたデータ
    $postback = $jsonObj->{'events'}[0]->{'postback'}->{'data'};
    
    if($postback === 'datetemp') {
        // 日にち選択時
        $response_format_text = array(
            'type' => 'text',
            'text' => '【'.$jsonObj->{'events'}[0]->{'postback'}->{'params'}->{'date'}.'】にご予約を承りました。'
        );
    } else if($postback === 'sanka') {
        $response_format_text = array(
            'type' => 'text',
            'text' => '参加を受け付けました。'
        );
    } else if($postback === 'fusanka') {
        $response_format_text = array(
            'type' => 'text',
            'text' => '不参加を受け付けました。'
        );
    }
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
