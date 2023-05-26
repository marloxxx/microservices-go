package dto

//LoginDTO is a model that used by client when POST from /login url
type LoginDTO struct {
	Email    string `json:"email" form:"email" binding:"required"` // binding required untuk memastikan bahwa email tidak boleh kosong
	Password string `json:"password" form:"password" binding:"required"` // binding required untuk memastikan bahwa password tidak boleh kosong
}
