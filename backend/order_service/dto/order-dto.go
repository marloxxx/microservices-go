package dto

type OrderCreateDTO struct {
	UserID        uint64  `json:"user_id" binding:"required"`
	Status        string  `json:"status" binding:"required"`
	TotalPrice    float64 `json:"total_price" binding:"required"`
	ShippingPrice float64 `json:"shipping_price" binding:"required"`
	PaymentStatus uint16  `json:"payment_status" binding:"required"`
	Courier       string  `json:"courier" binding:"required"`
}

type OrderUpdateDTO struct {
	ID            uint64  `json:"id" binding:"required"`
	UserID        uint64  `json:"user_id" binding:"required"`
	Status        string  `json:"status" binding:"required"`
	TotalPrice    float64 `json:"total_price" binding:"required"`
	ShippingPrice float64 `json:"shipping_price" binding:"required"`
	Courier       string  `json:"courier" binding:"required"`
	PaymentStatus uint16  `json:"payment_status" binding:"required"`
	SnapToken     string  `json:"snap_token" binding:"required"`
}
