package service

import (
	"log"

	"github.com/marloxxx/golang_gin_gorm_GWT/dto"
	"github.com/marloxxx/golang_gin_gorm_GWT/entity"
	"github.com/marloxxx/golang_gin_gorm_GWT/repository"
	"github.com/mashingan/smapping"
)

type UserService interface {
	Update(user dto.UserUpdateDTO) entity.User
	Profile(userID string) entity.User
}

// membuat interface user service dengan method update, profile

type userService struct {
	userRepository repository.UserRepository
}

// membuat struct user service dengan field user repository

func NewUserService(userRepo repository.UserRepository) UserService { // method dengan parameter user repository
	return &userService{
		userRepository: userRepo,
	}
}

func (service *userService) Update(user dto.UserUpdateDTO) entity.User { // method update untuk mengubah data user
	userToUpdate := entity.User{}                                        // membuat objek user
	err := smapping.FillStruct(&userToUpdate, smapping.MapFields(&user)) // memanggil method fill struct pada smapping
	if err != nil {                                                      // jika terjadi error
		log.Fatalf("Failed map %v:", err) // menampilkan error
	}
	updateUser := service.userRepository.UpdateUser(userToUpdate) // memanggil method update user pada user repository
	return updateUser                                             // mengembalikan nilai updateUser
}

func (service *userService) Profile(userID string) entity.User { // method profile untuk menampilkan data user
	return service.userRepository.ProfileUser(userID) // memanggil method profile user pada user repository
}
