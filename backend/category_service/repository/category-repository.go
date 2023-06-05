package repository

import (
	"github.com/marloxxx/microservices-go/backend/category_service/entity"
	"gorm.io/gorm"
)

type CategoryRepository interface {
	InsertCategory(category entity.Category) entity.Category
	UpdateCategory(category entity.Category) entity.Category
	All() []entity.Category
	FindByID(categoryID uint64) entity.Category
	DeleteCategory(category entity.Category)
}

type categoryConnection struct {
	connection *gorm.DB
}

func NewCategoryRepository(db *gorm.DB) CategoryRepository {
	return &categoryConnection{
		connection: db,
	}
}

func (db *categoryConnection) InsertCategory(category entity.Category) entity.Category {
	db.connection.Save(&category)
	return category
}

func (db *categoryConnection) UpdateCategory(category entity.Category) entity.Category {
	db.connection.Save(&category)
	return category
}

func (db *categoryConnection) All() []entity.Category {
	var categories []entity.Category
	db.connection.Find(&categories)
	return categories
}

func (db *categoryConnection) FindByID(categoryID uint64) entity.Category {
	var category entity.Category
	db.connection.Find(&category, categoryID)
	return category
}

func (db *categoryConnection) DeleteCategory(category entity.Category) {
	db.connection.Delete(&category)
}
