package service

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/order_service/dto"
	"github.com/marloxxx/microservices-go/backend/order_service/entity"
	"github.com/marloxxx/microservices-go/backend/order_service/repository"
	"github.com/mashingan/smapping"
)

// OrderItemService is a contract about something that this service can do
type OrderItemService interface {
	Insert(b dto.OrderItemCreateDTO) entity.OrderItem
}

type orderItemService struct {
	orderItemRepository repository.OrderItemRepository
}

// NewOrderItemService creates a new instance of OrderItemService
func NewOrderItemService(orderItemRepository repository.OrderItemRepository) OrderItemService {
	return &orderItemService{
		orderItemRepository: orderItemRepository,
	}
}

func (service *orderItemService) Insert(b dto.OrderItemCreateDTO) entity.OrderItem {
	orderItem := entity.OrderItem{}
	err := smapping.FillStruct(&orderItem, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v", err)
	}

	res := service.orderItemRepository.InsertOrderItem(orderItem)
	return res
}