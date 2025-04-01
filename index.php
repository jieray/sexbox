<?php
// 处理留言提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $message = htmlspecialchars(trim($_POST['message']));
    $date = date('Y-m-d H:i:s');

    if (!empty($name) && !empty($message)) {
        // 保存到文件
        $data = "[$date]|$name|$message\n";
        file_put_contents('messages.txt', $data, FILE_APPEND | LOCK_EX);
        
        // 防止重复提交
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 读取并处理留言
$messages = [];
if (file_exists('messages.txt')) {
    $messages = array_reverse(file('messages.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酷鱼留言板</title>
    <style>
        :root {
            --primary-color: #007bff;
            --hover-color: #0056b3;
            --background-color: #f8f9fa;
            --text-color: #212529;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            color: var(--text-color);
            background-color: #f0f2f5;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .message-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            transition: transform 0.2s;
        }

        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            color: #6c757d;
        }

        .author {
            font-weight: 600;
            color: var(--primary-color);
        }

        .timestamp {
            font-size: 0.9em;
        }

        .message-content {
            font-size: 1.1em;
            line-height: 1.7;
            color: #495057;
            white-space: pre-wrap;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
        }

        button {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: var(--hover-color);
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 留言板</h1>
        
        <form method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="请输入您的姓名" required>
            </div>
            <div class="form-group">
                <textarea name="message" rows="5" placeholder="写下您的问题或想法..." required></textarea>
            </div>
            <button type="submit">✨发布留言✨</button>
        </form>

        <h2>最新留言（<?= count($messages) ?>条）</h2>
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                <?php 
                    list($timestamp, $author, $content) = explode('|', $msg, 3);
                ?>
                <div class="message-card">
                    <div class="message-header">
                        <span class="author"><?= $author ?></span>
                        <span class="timestamp"><?= $timestamp ?></span>
                    </div>
                    <div class="message-content"><?= nl2br($content) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="message-card" style="text-align: center; color: #6c757d;">
               🎉快来第一个发言吧！
            </div>
        <?php endif; ?>
    </div>
</body>
</html>