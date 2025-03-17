<?php
// Kết nối database từ file config
require_once 'admin/inc/db_config.php';

// Lấy dữ liệu phòng từ database
function getRoomsData() {
    global $con; // Biến kết nối database từ db_config.php
    
    $roomsData = "";
    
    // Truy vấn lấy các phòng đang hoạt động (status = 1 và removed = 0)
    $query = "SELECT * FROM `rooms` WHERE `status` = 1 AND `removed` = 0";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Định dạng thông tin phòng để hiển thị trong prompt
            $roomsData .= "- Phòng {$row['name']}: Phù hợp cho {$row['adult']} người lớn";
            
            if ($row['children'] > 0) {
                $roomsData .= " và {$row['children']} trẻ em";
            }
            
            // Định dạng giá phòng
            $formattedPrice = number_format($row['price'], 0, ',', '.');
            $roomsData .= ", giá {$formattedPrice}đ/đêm";
            
            // Thêm diện tích nếu có
            if ($row['area'] > 0) {
                $roomsData .= ", diện tích {$row['area']}m²";
            }
            
            // Thêm mô tả nếu có
            if (!empty($row['description'])) {
                $roomsData .= ", {$row['description']}";
            }
            
            $roomsData .= "\n";
        }
    } else {
        $roomsData = "- Hiện tại không có thông tin phòng. Vui lòng liên hệ trực tiếp với chúng tôi để biết thêm chi tiết.\n";
    }
    
    return $roomsData;
}

// Hàm mới: Lấy dữ liệu tiện ích từ database
function getFacilitiesData() {
    global $con;
    
    $facilitiesData = "";
    
    // Truy vấn lấy các tiện ích từ bảng facilities
    $query = "SELECT * FROM `facilities`";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Định dạng thông tin tiện ích để hiển thị trong prompt
            $facilitiesData .= "- {$row['name']}";
            
            // Thêm mô tả nếu có
            if (!empty($row['description'])) {
                $facilitiesData .= ": {$row['description']}";
            }
            
            $facilitiesData .= "\n";
        }
    } else {
        $facilitiesData = "- Hiện tại không có thông tin về tiện ích. Vui lòng liên hệ trực tiếp với chúng tôi để biết thêm chi tiết.\n";
    }
    
    return $facilitiesData;
}

// Lấy dữ liệu phòng và tiện ích để sử dụng trong chatbot
$roomsInfo = getRoomsData();
$facilitiesInfo = getFacilitiesData();
?>

<!-- CSS cho chatbot -->
<style>
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        width: 350px;
    }
    
    .chat-box {
        display: none;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        height: 400px;
        overflow: hidden;
        flex-direction: column;
    }
    
    .chat-header {
        background: #2ec1ac; /* Sử dụng màu chủ đạo của website */
        color: white;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    
    .message {
        margin-bottom: 10px;
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 18px;
    }
    
    .user-message {
        background: #E3F2FD;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }
    
    .bot-message {
        background: #F5F5F5;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }
    
    .chat-input {
        display: flex;
        padding: 10px;
        border-top: 1px solid #eee;
    }
    
    .chat-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
    }
    
    .chat-input button {
        background: #2ec1ac; /* Sử dụng màu chủ đạo của website */
        color: white;
        border: none;
        padding: 10px 15px;
        margin-left: 10px;
        border-radius: 20px;
        cursor: pointer;
    }
    
    .chat-button {
        background: #2ec1ac; /* Sử dụng màu chủ đạo của website */
        color: white;
        border: none;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        align-self: flex-end;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }
</style>

<!-- Chat bot container -->
<div class="chat-container">
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <div>Hỗ trợ đặt phòng khách sạn</div>
            <button class="close-btn" onclick="toggleChat()">✕</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot-message">Xin chào! Bạn cần hỗ trợ gì?</div>
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Nhập tin nhắn..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>
    <button class="chat-button" onclick="toggleChat()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>
</div>

<script>
    // JavaScript cho chatbot
    function toggleChat() {
        const chatBox = document.getElementById('chatBox');
        if (chatBox.style.display === 'flex') {
            chatBox.style.display = 'none';
        } else {
            chatBox.style.display = 'flex';
        }
    }
    
    function sendMessage() {
        const userInput = document.getElementById('userInput');
        const message = userInput.value.trim();
        
        if (message !== '') {
            addMessage(message, 'user');
            userInput.value = '';
            
            // Gửi tin nhắn tới API và nhận phản hồi
            fetchBotResponse(message);
        }
    }
    
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }
    
    function addMessage(message, sender) {
        const chatMessages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(sender === 'user' ? 'user-message' : 'bot-message');
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        
        // Cuộn xuống tin nhắn mới nhất
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    async function fetchBotResponse(userMessage) {
        try {
            const API_KEY = 'AIzaSyBtvdUl38OWkch6cZ9J19fD449mDxrogxU';
            const API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
            
            // Sử dụng dữ liệu phòng và tiện ích từ database
            const systemPrompt = `Bạn là trợ lý ảo chuyên hỗ trợ về đặt phòng khách sạn.
            Nhiệm vụ của bạn là:
            1. Chỉ trả lời các câu hỏi liên quan đến khách sạn, đặt phòng, dịch vụ phòng, giá cả, tiện nghi, chính sách huỷ phòng, và thông tin liên quan đến lưu trú tại khách sạn.
            2. Nếu người dùng hỏi về chủ đề không liên quan đến khách sạn, hãy lịch sự từ chối và hướng họ quay lại chủ đề về đặt phòng khách sạn.
            3. Cung cấp thông tin hữu ích, ngắn gọn và chính xác.
            4. Mọi câu trả lời phải bằng tiếng Việt.
            5. Không cung cấp các thông tin cá nhân hoặc thông tin không liên quan đến khách sạn.
            
            THÔNG TIN PHÒNG KHÁCH SẠN (DỮ LIỆU THỰC TẾ TỪ DATABASE):
            <?php echo $roomsInfo; ?>
            
            TIỆN ÍCH KHÁCH SẠN (DỮ LIỆU THỰC TẾ TỪ DATABASE):
            <?php echo $facilitiesInfo; ?>
            
            CHÍNH SÁCH KHÁCH SẠN:
            - Nhận phòng: sau 14:00
            - Trả phòng: trước 12:00
            - Huỷ phòng miễn phí: trước 48 giờ
            - Trẻ em dưới 6 tuổi: miễn phí khi ở cùng bố mẹ
            
            Khi người dùng hỏi về lựa chọn phòng cho số lượng người cụ thể, hãy đề xuất loại phòng phù hợp dựa trên thông tin phòng thực tế trên.
            Khi người dùng hỏi về tiện ích, hãy cung cấp thông tin từ mục TIỆN ÍCH KHÁCH SẠN ở trên.`;
            
            // Sửa đổi API endpoint từ gemini-2.0-flash sang gemini-pro
            const response = await fetch(`${API_URL}?key=${API_KEY}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [
                        {
                            role: 'user',
                            parts: [{text: systemPrompt + "\n\nCâu hỏi của người dùng: " + userMessage}]
                        }
                    ],
                    generationConfig: {
                        temperature: 0.2,
                        maxOutputTokens: 800,
                    }
                })
            });
            
            const data = await response.json();
            
            if (data.candidates && data.candidates[0].content && data.candidates[0].content.parts) {
                const botMessage = data.candidates[0].content.parts[0].text;
                addMessage(botMessage, 'bot');
            } else {
                console.error('API response:', data);
                if (data.error) {
                    console.error('API error:', data.error);
                }
                addMessage('Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ trực tiếp qua số hotline của khách sạn.', 'bot');
            }
        } catch (error) {
            console.error('Error details:', error);
            addMessage('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại sau.', 'bot');
        }
    }
</script>