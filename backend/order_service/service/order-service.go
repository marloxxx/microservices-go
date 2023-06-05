package service

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/order_service/dto"
	"github.com/marloxxx/microservices-go/backend/order_service/entity"
	"github.com/marloxxx/microservices-go/backend/order_service/repository"
	"github.com/mashingan/smapping"
)

// OrderService is a contract about something that this service can do
type OrderService interface {
	Insert(b dto.OrderCreateDTO) entity.Order
	Update(b dto.OrderUpdateDTO) entity.Order
	Delete(b entity.Order)
	Approve(b entity.Order) entity.Order
	Reject(b entity.Order) entity.Order
	Cancel(b entity.Order) entity.Order
	All(userID uint64) []entity.Order
	FindByID(orderID uint64) entity.Order
}

type orderService struct {
	orderRepository repository.OrderRepository
}

// NewOrderService creates a new instance of OrderService
func NewOrderService(orderRepository repository.OrderRepository) OrderService {
	return &orderService{
		orderRepository: orderRepository,
	}
}

func (service *orderService) All(userID uint64) []entity.Order {
	return service.orderRepository.All(userID)
}

func (service *orderService) FindByID(orderID uint64) entity.Order {
	return service.orderRepository.FindByID(orderID)
}

func (service *orderService) Insert(b dto.OrderCreateDTO) entity.Order {
	order := entity.Order{}
	err := smapping.FillStruct(&order, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v", err)
	}

	res := service.orderRepository.InsertOrder(order)
	return res
}

func (service *orderService) Update(b dto.OrderUpdateDTO) entity.Order {
	order := entity.Order{}
	err := smapping.FillStruct(&order, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v", err)
	}

	res := service.orderRepository.UpdateOrder(order)
	return res
}

func (service *orderService) Delete(b entity.Order) {
	service.orderRepository.DeleteOrder(b)
}

func (service *orderService) Approve(b entity.Order) entity.Order {
	b.Status = "approved"
	res := service.orderRepository.AppoveOrder(b)
	return res
}

func (service *orderService) Reject(b entity.Order) entity.Order {
	b.Status = "rejected"
	res := service.orderRepository.RejectOrder(b)
	return res
}

func (service *orderService) Cancel(b entity.Order) entity.Order {
	b.Status = "canceled"
	res := service.orderRepository.CancelOrder(b)
	return res
}
