package entity

import "gorm.io/gorm"

type OrderItem struct {
	gorm.Model
	ID           uint64 `gorm:"primary_key:auto_increment" json:"id"`
	OrderID      uint64 `gorm:"type:bigint(20)" json:"order_id"`
	ProductName  string `gorm:"type:varchar(255)" json:"product_name"`
	ProductImage string `gorm:"type:text" json:"product_image"`
	Quantity     uint64 `gorm:"type:bigint(20)" json:"quantity"`
	Price        uint64 `gorm:"type:bigint(20)" json:"price"`
}
