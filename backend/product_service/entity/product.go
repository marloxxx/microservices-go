package entity

import "gorm.io/gorm"

type Product struct {
	gorm.Model
	ID           uint64 `gorm:"primary_key:auto_increment"`
	Name         string `gorm:"type:varchar(255)" json:"name" validate:"required,min=3,max=255"`
	Description  string `gorm:"type:varchar(255)" json:"description" validate:"required,min=3,max=255"`
	Price        uint64 `gorm:"type:int" json:"price" validate:"required"`
	Quantity     uint64 `gorm:"type:int" json:"quantity" validate:"required"`
	Image        string `gorm:"type:varchar(255)" json:"image" validate:"required,url"`
	CategoryID   uint64 `gorm:"type:int" json:"category_id" validate:"required"`
	CategoryName string `gorm:"type:varchar(255)" json:"category_name" validate:"required"`
}
