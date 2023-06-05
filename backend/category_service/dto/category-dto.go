package dto

type CategoryUpdateDTO struct {
	ID          uint64 `json:"id" form:"id" binding:"required"`
	Name        string `json:"name" form:"name" binding:"required,min=3,max=255"`
	Description string `json:"description" form:"description" binding:"required,min=3,max=255"`
	Image       string `json:"image" form:"image" binding:"required,url"`
}

type CategoryCreateDTO struct {
	Name        string `json:"name" form:"name" binding:"required,min=3,max=255"`
	Description string `json:"description" form:"description" binding:"required,min=3,max=255"`
	Image       string `json:"image" form:"image" binding:"required,url"`
}
