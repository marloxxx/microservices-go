package repository

import (
	"github.com/marloxxx/microservices-go/backend/product_service/entity"
	"gorm.io/gorm"
)

type ProductRepository interface {
	InsertProduct(product entity.Product) entity.Product
	UpdateProduct(product entity.Product) entity.Product
	All() []entity.Product
	FindByID(ProductID uint64) entity.Product
	DeleteProduct(product entity.Product)
}

type productConnection struct {
	connection *gorm.DB
}

func NewProductRepository(db *gorm.DB) ProductRepository {
	return &productConnection{
		connection: db,
	}
}

func (db *productConnection) InsertProduct(product entity.Product) entity.Product {
	db.connection.Save(&product)
	return product
}

func (db *productConnection) UpdateProduct(product entity.Product) entity.Product {
	db.connection.Save(&product)
	return product
}

func (db *productConnection) All() []entity.Product {
	var products []entity.Product
	db.connection.Find(&products)
	return products
}

func (db *productConnection) FindByID(productID uint64) entity.Product {
	var product entity.Product
	db.connection.Find(&product, productID)
	return product
}

func (db *productConnection) DeleteProduct(product entity.Product) {
	db.connection.Delete(&product)
}
