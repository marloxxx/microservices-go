package dto

//UserUpdateDTO is used by client when PUT update profile
type UserUpdateDTO struct {
	ID       uint64 `json:"id" form:"id"`                                                    // binding required untuk memastikan bahwa id tidak boleh kosong
	Name     string `json:"name" form:"name" binding:"required"`                             // binding required untuk memastikan bahwa name tidak boleh kosong
	Email    string `json:"email" form:"email" binding:"required,email"`                     // binding required untuk memastikan bahwa email tidak boleh kosong dan harus sesuai dengan format email
	Password string `json:"password,omitempty" form:"password,omitempty" binding:"required"` // binding required untuk memastikan bahwa password tidak boleh kosong
}

//UserCreateDTO is used by client when create user
// type UserCreateDTO struct {
// 	Name     string `json:"name" form:"name" binding:"required"`
// 	Email    string `json:"email" form:"email" binding:"required" validate:"email"`
// 	Password string `json:"password, omitempty" form:"password, omitempty" validate:"min:6" binding:"required" `
// }
