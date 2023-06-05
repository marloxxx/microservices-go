package repository

import (
	"github.com/marloxxx/microservices-go/backend/order_service/entity"
	"gorm.io/gorm"
)

type OrderItemRepository interface {
	InsertOrderItem(order entity.OrderItem) entity.OrderItem
}

type orderItemConnection struct {
	connection *gorm.DB
}

func NewOrderItemRepository(db *gorm.DB) OrderItemRepository {
	return &orderItemConnection{
		connection: db,
	}
}

func (db *orderItemConnection) InsertOrderItem(orderItem entity.OrderItem) entity.OrderItem {
	// insert order item
	db.connection.Save(&orderItem)
	return orderItem
}
