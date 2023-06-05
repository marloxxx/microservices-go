package dto

type OrderItemCreateDTO struct {
	OrderID      uint64 `json:"order_id" binding:"required"`
	ProductName  string `json:"product_name" binding:"required"`
	ProductImage string `json:"product_image" binding:"required"`
	Quantity     uint64 `json:"quantity" binding:"required"`
	Price        uint64 `json:"price" binding:"required"`
}
