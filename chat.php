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
            $roomsData .= ", giá {$formattedPrice}VND/đêm";
            
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

// Hàm mới: Lấy dữ liệu hình ảnh phòng
function getRoomImages() {
    global $con;
    
    $roomImages = array();
    
    // Truy vấn lấy các phòng đang hoạt động
    $room_query = "SELECT * FROM `rooms` WHERE `status` = 1 AND `removed` = 0";
    $room_result = mysqli_query($con, $room_query);
    
    if (mysqli_num_rows($room_result) > 0) {
        while ($room = mysqli_fetch_assoc($room_result)) {
            $room_id = $room['id'];
            $room_name = $room['name'];
            
            // Lấy hình ảnh đại diện (thumb)
            $thumb_query = "SELECT * FROM `room_images` WHERE `room_id` = $room_id AND `thumb` = 1";
            $thumb_result = mysqli_query($con, $thumb_query);
            
            $thumb_image = "";
            if (mysqli_num_rows($thumb_result) > 0) {
                $thumb_data = mysqli_fetch_assoc($thumb_result);
                $thumb_image = "./images/rooms/" . $thumb_data['image'];
            } else {
                // Nếu không có thumb, lấy một hình ảnh bất kỳ
                $any_img_query = "SELECT * FROM `room_images` WHERE `room_id` = $room_id LIMIT 1";
                $any_img_result = mysqli_query($con, $any_img_query);
                
                if (mysqli_num_rows($any_img_result) > 0) {
                    $any_img_data = mysqli_fetch_assoc($any_img_result);
                    $thumb_image = "./images/rooms/" . $any_img_data['image'];
                } else {
                    // Nếu không có hình ảnh nào, dùng hình mặc định
                    $thumb_image = "./images/rooms/thumb.png";
                }
            }
            
            // Lưu thông tin phòng và hình ảnh
            $roomImages[$room_name] = array(
                'image' => $thumb_image,
                'id' => $room_id
            );
        }
    }
    
    return $roomImages;
}

// Lấy dữ liệu phòng và tiện ích để sử dụng trong chatbot
$roomsInfo = getRoomsData();
$facilitiesInfo = getFacilitiesData();
$roomImages = getRoomImages();
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Thêm Bootstrap Icons từ CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<!-- CSS cho chatbot -->
<style>
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        width: 400px;
    }
    
    .chat-box {
        display: none;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.15);
        height: 550px;
        overflow: hidden;
        flex-direction: column;
    }
    
    .chat-header {
        background: #2ec1ac;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        font-size: 16px;
    }
    
    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #f8f9fa;
    }
    
    .message {
        margin-bottom: 10px;
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 18px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .user-message {
        background: #E3F2FD;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
        color: #1565C0;
    }
    
    .bot-message {
        background: white;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
        color: #333;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        width: 100%;
    }
    
    .chat-input {
        display: flex;
        padding: 15px;
        background: white;
        border-top: 1px solid #eee;
        gap: 10px;
    }
    
    .chat-input input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
        font-size: 14px;
    }
    
    .chat-input input:focus {
        border-color: #2ec1ac;
    }
    
    .chat-input button {
        background: #2ec1ac;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .chat-input button:hover {
        background: #279e8c;
        transform: translateY(-1px);
    }
    
    .chat-button {
        background: #2ec1ac;
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
        transition: all 0.3s ease;
    }
    
    .chat-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }
    
    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .close-btn:hover {
        opacity: 1;
    }
    
    .room-image-container {
        margin: 10px 0;
        border-radius: 12px;
        overflow: visible;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .room-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        border-radius: 12px 12px 0 0;
    }
    
    .room-info {
        padding: 12px;
        font-size: 15px;
        color: #333;
        text-align: center;
        background: white;
        font-weight: 500;
    }
    
    .room-actions {
        padding: 12px;
        display: flex;
        justify-content: center;
        background: white;
        margin-bottom: 10px;
    }
    
    .room-action-btn {
        background-color: #2ec1ac;
        color: white !important;
        padding: 12px 24px;
        border-radius: 25px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(46, 193, 172, 0.3);
        white-space: nowrap;
        width: auto;
        min-width: fit-content;
    }
    
    .room-action-btn:hover {
        background-color: #279e8c;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(46, 193, 172, 0.4);
        color: white !important;
    }
    
    .room-action-btn i {
        font-size: 18px;
        display: inline-block;
        vertical-align: middle;
    }
    
    .image-carousel {
        display: flex;
        overflow-x: auto;
        gap: 8px;
        padding: 5px 0;
        scrollbar-width: thin;
        max-width: 100%;
    }
    
    .image-carousel::-webkit-scrollbar {
        height: 5px;
    }
    
    .image-carousel::-webkit-scrollbar-thumb {
        background-color: rgba(46, 193, 172, 0.5);
        border-radius: 10px;
    }
    
    .carousel-item {
        flex: 0 0 auto;
        width: 100px;
        height: 75px;
        overflow: hidden;
        border-radius: 5px;
        border: 2px solid white;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .carousel-item:hover {
        transform: translateY(-3px);
        border-color: #2ec1ac;
    }
    
    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
    
    function addMessage(message, sender, imageData = null) {
        const chatMessages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(sender === 'user' ? 'user-message' : 'bot-message');
        
        // Nếu là text thông thường
        if (typeof message === 'string') {
            messageDiv.textContent = message;
        } 
        // Nếu là HTML (có hình ảnh)
        else if (message.html) {
            messageDiv.innerHTML = message.html;
        }
        
        // Thêm hình ảnh nếu có
        if (imageData) {
            console.log("Image Data:", imageData);
            
            const imageContainer = document.createElement('div');
            imageContainer.className = 'room-image-container';
            
            const image = document.createElement('img');
            image.src = imageData.src;
            image.alt = imageData.alt || 'Hình ảnh phòng';
            image.className = 'room-image';
            image.style.maxWidth = '100%';
            image.style.height = 'auto';
            
            imageContainer.appendChild(image);
            
            if (imageData.caption) {
                const caption = document.createElement('div');
                caption.className = 'room-info';
                caption.textContent = imageData.caption;
                imageContainer.appendChild(caption);
            }
            
            // Thêm nút xem chi tiết và đặt phòng với inline styles
            const actionsDiv = document.createElement('div');
            actionsDiv.style.display = 'flex';
            actionsDiv.style.justifyContent = 'center';
            actionsDiv.style.marginTop = '10px';
            actionsDiv.style.gap = '10px';
            
            const viewDetailsLink = document.createElement('a');
            viewDetailsLink.href = `room_details.php?id=${imageData.roomId}`;
            viewDetailsLink.style.backgroundColor = '#2ec1ac';
            viewDetailsLink.style.color = 'white';
            viewDetailsLink.style.padding = '8px 15px';
            viewDetailsLink.style.borderRadius = '20px';
            viewDetailsLink.style.textDecoration = 'none';
            viewDetailsLink.style.display = 'inline-flex';
            viewDetailsLink.style.alignItems = 'center';
            viewDetailsLink.style.gap = '5px';
            viewDetailsLink.style.fontSize = '14px';
            viewDetailsLink.style.cursor = 'pointer';
            viewDetailsLink.style.transition = 'all 0.3s ease';
            
            // Thêm icon và text cho nút
            const icon = document.createElement('i');
            icon.className = 'bi bi-info-circle';
            viewDetailsLink.appendChild(icon);
            
            const buttonText = document.createTextNode(' Xem chi tiết & Đặt phòng');
            viewDetailsLink.appendChild(buttonText);
            
            // Thêm hover effect
            viewDetailsLink.onmouseover = function() {
                this.style.backgroundColor = '#279e8c';
                this.style.transform = 'translateY(-2px)';
            };
            viewDetailsLink.onmouseout = function() {
                this.style.backgroundColor = '#2ec1ac';
                this.style.transform = 'translateY(0)';
            };
            
            actionsDiv.appendChild(viewDetailsLink);
            imageContainer.appendChild(actionsDiv);
            
            console.log("Button created with href:", viewDetailsLink.href);
            console.log("Actions div children count:", actionsDiv.children.length);
            
            messageDiv.appendChild(imageContainer);
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Hàm kiểm tra tin nhắn có yêu cầu về thông tin phòng hay không
    function checkForRoomInfo(message, response) {
        const roomImages = <?php echo json_encode($roomImages); ?>;
        console.log("Available rooms:", roomImages); // Debug: Log ra danh sách phòng
        
        for (const roomName in roomImages) {
            const normalizedRoomName = roomName.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const normalizedMessage = message.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const normalizedResponse = response.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            
            console.log("Checking room:", roomName); // Debug: Log ra tên phòng đang kiểm tra
            
            if (normalizedMessage.includes(normalizedRoomName) || normalizedResponse.includes(normalizedRoomName)) {
                console.log("Room found:", roomName); // Debug: Log ra khi tìm thấy phòng
                const imageData = {
                    src: roomImages[roomName].image,
                    alt: roomName,
                    caption: `Phòng ${roomName}`,
                    roomId: roomImages[roomName].id
                };
                console.log("Image data created:", imageData); // Debug: Log ra dữ liệu hình ảnh
                return imageData;
            }
        }
        
        return null;
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
                
                // Kiểm tra xem tin nhắn có hỏi về phòng nào không
                const imageData = checkForRoomInfo(userMessage, botMessage);
                
                // Hiển thị tin nhắn văn bản trước
                addMessage(botMessage, 'bot');
                
                // Nếu tìm thấy thông tin phòng, hiển thị hình ảnh
                if (imageData) {
                    // Đợi một chút trước khi hiển thị hình ảnh để tạo cảm giác tự nhiên
                    setTimeout(() => {
                        addMessage({ html: `Đây là hình ảnh của ${imageData.caption}:` }, 'bot', imageData);
                    }, 500);
                }
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
</body>
</html>