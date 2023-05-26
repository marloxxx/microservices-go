package dto

//RegisterDTO used when client post from /register url
type RegisterDTO struct {
	Name     string `json:"name" form:"name" binding:"required"` // binding required untuk memastikan bahwa name tidak boleh kosong
	Email    string `json:"email" form:"email" binding:"required,email"` // binding required untuk memastikan bahwa email tidak boleh kosong dan harus sesuai dengan format email
	Password string `json:"password" form:"password" binding:"required"` // binding required untuk memastikan bahwa password tidak boleh kosong
}
