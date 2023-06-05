package dto

type ProductUpdateDTO struct {
	ID           uint64 `json:"id" form:"id" binding:"required"`
	Name         string `json:"name" form:"name" binding:"required,min=3,max=255"`
	Description  string `json:"description" form:"description" binding:"required,min=3,max=255"`
	Price        uint64 `json:"price" form:"price" binding:"required"`
	Quantity     uint64 `json:"quantity" form:"quantity" binding:"required"`
	Image        string `json:"image" form:"image" binding:"required,url"`
	CategoryID   uint64 `json:"category_id" form:"category_id" binding:"required"`
	CategoryName string `json:"category_name" form:"category_name" binding:"required"`
}

type ProductCreateDTO struct {
	Name         string `json:"name" form:"name" binding:"required,min=3,max=255"`
	Description  string `json:"description" form:"description" binding:"required,min=3,max=255"`
	Quantity     uint64 `json:"quantity" form:"quantity" binding:"required"`
	Price        uint64 `json:"price" form:"price" binding:"required"`
	Image        string `json:"image" form:"image" binding:"required,url"`
	CategoryID   uint64 `json:"category_id" form:"category_id" binding:"required"`
	CategoryName string `json:"category_name" form:"category_name" binding:"required"`
}
