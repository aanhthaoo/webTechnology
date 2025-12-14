-- Add sample lessons to courses
USE onlinecourse;

INSERT INTO lessons (course_id, title, content, `order`, duration) VALUES 
(1, 'Gioi thieu ve PHP', 'Tim hieu ve ngon ngu lap trinh PHP va ung dung', 1, '30 phut'),
(1, 'Cai dat moi truong', 'Huong dan cai dat XAMPP va thiet lap moi truong phat trien', 2, '45 phut'),
(1, 'Bien va kieu du lieu', 'Hoc ve bien, hang so va cac kieu du lieu trong PHP', 3, '60 phut'),
(1, 'Cau truc dieu khien', 'Tim hieu ve if-else, for, while trong PHP', 4, '50 phut'),
(1, 'Lam viec voi MySQL', 'Ket noi va thao tac voi co so du lieu MySQL', 5, '75 phut'),

(2, 'Nguyen tac thiet ke UI', 'Tim hieu cac nguyen tac co ban trong thiet ke giao dien', 1, '40 phut'),
(2, 'Nghien cuu nguoi dung', 'Phuong phap nghien cuu va phan tich nguoi dung', 2, '50 phut'),
(2, 'Wireframe va Prototype', 'Tao wireframe va prototype cho du an thiet ke', 3, '65 phut'),
(2, 'Mau sac va Typography', 'Lua chon mau sac va font chu phu hop', 4, '45 phut'),
(2, 'Thiet ke responsive', 'Thiet ke giao dien tren nhieu thiet bi khac nhau', 5, '70 phut');