package dto

type CartUpdateDTO struct {
	ID           uint64 `json:"id" form:"id" binding:"required"`
	ProductID    uint64 `json:"product_id" form:"product_id" binding:"required"`
	ProductImage string `json:"product_image" form:"product_image" binding:"required"`
	ProductName  string `json:"product_name" form:"product_name" binding:"required"`
	Quantity     uint64 `json:"quantity" form:"quantity" binding:"required"`
	Price        uint64 `json:"price" form:"price" binding:"required"`
	Total        uint64 `json:"total" form:"total" binding:"required"`
	UserID       uint64 `json:"user_id" form:"user_id" binding:"required"`
}

type CartCreateDTO struct {
	ProductID    uint64 `json:"product_id" form:"product_id" binding:"required"`
	ProductImage string `json:"product_image" form:"product_image" binding:"required"`
	ProductName  string `json:"product_name" form:"product_name" binding:"required"`
	Quantity     uint64 `json:"quantity" form:"quantity" binding:"required"`
	Price        uint64 `json:"price" form:"price" binding:"required"`
	Total        uint64 `json:"total" form:"total" binding:"required"`
	UserID       uint64 `json:"user_id" form:"user_id" binding:"required"`
}
