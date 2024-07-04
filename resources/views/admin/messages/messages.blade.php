<x-app-layout>
@section('content')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
    <script src="https://kit.fontawesome.com/c609c0bad9.js" crossorigin="anonymous"></script>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        background-color: #f0f0f0;
    }

    .actions input {
        width: 100%;
        height: 40px;
        border-radius: 30px;
    }

    h1 {
        text-align: left;
        font-size: 25px;
        padding-left: 0.70rem;
        font-weight: bold;
    }

    .chat-container {
        display: flex;
        height: calc(100vh - 60px);
        margin-top: 3px;
    }

    .users-list {
        width: 25%;
        background-color: #fff;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        padding: 10px;
    }

    .user-item {
        cursor: pointer;
        padding: 10px;
        display: flex;
        align-items: center;
    }

    .user-item:hover, .selected {
        background-color: #e8ecf0;
    }

    .user-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .recent-message {
        font-size: 12px;
        color: #666;
    }

    .chat-box {
        width: 75%;
        background-color: #fff;
        display: flex;
        flex-direction: column;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        display: none; /* Hide all chat panels initially */
    }

    .chat-input {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .chat-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: none;
        margin-right: 10px;
    }

    .chat-input button {
        width: 10%;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .admin, .others {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
    }

    .admin {
        background-color: #4bd39c;
        text-align: right;
        color: #fff;
    }

    .others {
        background-color: #e5e5e5;
        text-align: left;
    }
</style>
<body>
    <div class="chat-container">
        <div class="users-list" id="users">
            <div>
                <h1>Messages</h1>
            </div>
            <div class="actions">
                <input class="form-control" type="search" placeholder="Search">
            </div>
            <br>
            @foreach ($users as $user)
                <div class="user-item" data-username="{{ $user->name }}" data-userid="{{ $user->id }}">
                    <div>
                        {{ $user->name }}
                        <div class="recent-message" id="recent-{{ $user->name }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="chat-box" id="chat-box">
            @foreach ($users as $user)
                <div id="chat-panel-{{ $user->name }}" class="chat-messages">
                    <!-- Chat messages for {{ $user->name }} -->
                    @foreach ($messages as $message)
                        @if ($message->sender_id == auth()->id() && $message->recipient_id == $user->id)
                            <div class="admin">
                                <p>You</p>
                                <p>{{ $message->message }}</p>
                                </div>
                        @elseif ($message->sender_id == $user->id && $message->recipient_id == auth()->id())
                            <div class="others">
                                <p>{{ $user->name }}</p>
                                <p>{{ $message->message }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach

            <form method="post" action="{{ route('admin.messages.store') }}" class="chat-input">
                @csrf
                <input type="hidden" id="recipient_id" name="recipient_id" value="">
                <input placeholder="Type your message..." rows="3" type="text" class="form-control" id="message" name="message" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to user items
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                selectUser(item.dataset.username, item.dataset.userid);
            });
        });

        // Select the default user
        let firstUser = document.querySelector('.user-item');
        if (firstUser) {
            selectUser(firstUser.dataset.username, firstUser.dataset.userid);
        }
    });

    function selectUser(username, userid) {
        selectedUser = username;
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('selected');
        });
        document.querySelector(`.user-item[data-username="${username}"]`).classList.add('selected');
        showChatPanel(username);

        // Set the recipient_id in the form
        document.getElementById('recipient_id').value = userid;
    }

    function showChatPanel(username) {
        // Hide all chat panels
        document.querySelectorAll('.chat-messages').forEach(panel => {
            panel.style.display = 'none';
        });

        // Show the selected user's chat panel
        let chatPanel = document.getElementById(`chat-panel-${username}`);
        if (chatPanel) {
            chatPanel.style.display = 'block';
        } 
    }
</script>

</html>
</body>
@endsection

@section('title')
    Messages
@endsection

</x-app-layout>

