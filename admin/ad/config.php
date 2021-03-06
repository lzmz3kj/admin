<?php

return [
    //加载本模块默认参数
    'sourceList' => [
        'pc' => [
            'Discuzx' => [1 => '首页', 3 => '列表页', 4 => '详情页', 5 => '发布页'],
            'ClassAd' => [1 => '首页',2 => '列表页', 3 => '详情页', 4 => '发布页'],
        ],
        'app' => [
            'Discuzx' => [1 => '首页幻灯片',3 => '列表页推广', 5 => '餐馆幻灯片', 6 => '启动屏', 7 => '首页热门推广', 8 => '搜索推广' , 9 => '详情页' ,10 => '列表页幻灯片',12 =>'小程序幻灯片',13 => '小程序详情页',14 => '新闻列表页推广'],
            'ClassAd' => [1 => '列表页幻灯片',2 =>'列表页推广',3 => '详情页广告']
        ]
    ],
    'img_url' => 'http://'.$_SERVER['SERVER_NAME'].'/',
    //论坛国家ID
    'cityid' => [
        'fr' => 1, 'it' => 2,'es' => 3,'en' => 4,'jp' => 5 ,'de' => 6,'ro' => 7,'dz' => 8,'se' => 9,'ch' => 11,
    ],
    'countryType' => [
        'fr' => '法国',
        'it' => '意大利',
        'es' => '西班牙',
        'de' => '德国',
        'en' => '英国',
        'ho' => '荷兰',
        'ao' => '奥地利',
        'lu' => '卢森堡',
        'bi' => '比利时',
        'pu' => '葡萄牙',
        'ro' => '罗马尼亚',
        'jp' => '日本',
        'ag' => '阿尔及利亚',
        'bo' => '波兰',
        'dm' => '丹麦',
        'jie' => '捷克',
        'rd' => '瑞典',
        'rs' => '瑞士',
        'xyl' => '匈牙利',
        'xl' => '希腊'
    ],
    //论坛位置
    'ctype' => [
        1 => '图片置顶',2 => '中介用户',3 => '左侧浮动1',4 => '左侧浮动2',5 => '右侧浮动1',6 => '右侧浮动2',7 => '主导航下广告1',8 => '主导航下广告2',
        9 => '主导航下广告3',10 => '主导航下广告4',11 => '主导航下广告5',12 => '主导航下广告6',13 => '主导航下广告7',14 => '主导航下广告8',15 => '主导航下广告9',
        16 => '主导航下广告10',
    ],
];