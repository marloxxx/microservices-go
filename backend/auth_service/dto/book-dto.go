package dto

//BookUpdateDTO is a model that client use when updateing a book
type BookUpdateDTO struct {
	ID          uint64 `json:"id" binding:"required"`                             // binding required untuk memastikan bahwa id tidak boleh kosong
	Title       string `json:"title" form:"title" binding:"required"`             // binding required untuk memastikan bahwa title tidak boleh kosong
	Description string `json:"description" form:"description" binding:"required"` // binding required untuk memastikan bahwa description tidak boleh kosong
	UserID      uint64 `json:"user_id,omitempty" form:"user_id, omitempty"`       // binding required untuk memastikan bahwa user id tidak boleh kosong
}

//BookCreateDTO is a model that client use when create a new book
type BookCreateDTO struct {
	Title       string `json:"title" form:"title" binding:"required"`             // binding required untuk memastikan bahwa title tidak boleh kosong
	Description string `json:"description" form:"description" binding:"required"` // binding required untuk memastikan bahwa description tidak boleh kosong
	UserID      uint64 `json:"user_id,omitempty" form:"user_id,omitempty"`        // binding required untuk memastikan bahwa user id tidak boleh kosong
}
