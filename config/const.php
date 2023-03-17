<?php

return [
    'Roles' => [
        'ROOT_ADMIN' => 0,      // システム管理者
        'ADMIN' => 5,           // 運営
        'WORKER' => 10,         // 施設職員
        'PARENT' => 15,         // 保護者
        'TEACHER' => 20,        // 教員
    ],
    'WorkerPermit' => [
        'FACILITIE_ADMIN' => 2, // 施設管理者
        'JOINT_WORKER' => 4,    // 共同アカウント
        'WORKER' => 5,          // 正社員
        'PART' => 10,           // アルバイト
    ]
];