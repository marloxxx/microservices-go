package service

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/auth_service/dto"
	"github.com/marloxxx/microservices-go/backend/auth_service/entity"
	"github.com/marloxxx/microservices-go/backend/auth_service/repository"
	"github.com/mashingan/smapping"
	"golang.org/x/crypto/bcrypt"
)

// AuthService is a contract about something that this service can do
type AuthService interface {
	VerifyCredential(email string, password string) interface{}
	CreateUser(user dto.RegisterDTO) entity.User
	FindByEmail(email string) entity.User
	IsDuplicateEmail(email string) bool
}

// membuat interface auth service dengan method verify credential, create user, find by email, is duplicate email

type authService struct {
	userRepository repository.UserRepository
}

// membuat struct auth service dengan field user repository

func NewAuthService(userRep repository.UserRepository) AuthService { // method dengan parameter user repository
	return &authService{
		userRepository: userRep,
	}
}

func (service *authService) VerifyCredential(email string, password string) interface{} { // method verify credential untuk memverifikasi email dan password
	res := service.userRepository.VerifyCredential(email, password) // memanggil method verify credential pada user repository
	if v, ok := res.(entity.User); ok {                             // jika res bertipe data entity user
		comparePassword := comparePassword(v.Password, []byte(password)) // memanggil method compare password
		if v.Email == email && comparePassword {                         // jika email dan password sama
			return res // mengembalikan nilai res
		}
		return false // mengembalikan nilai false
	}
	return res // mengembalikan nilai res
}

func (service *authService) CreateUser(user dto.RegisterDTO) entity.User { // method create user untuk membuat user
	userToCreate := entity.User{}                                        // membuat objek user to create
	err := smapping.FillStruct(&userToCreate, smapping.MapFields(&user)) // memanggil method fill struct
	if err != nil {                                                      // jika error tidak kosong
		log.Fatalf("Failed map %v", err) // menampilkan error
	}
	res := service.userRepository.InsertUser(userToCreate) // memanggil method insert user pada user repository
	return res                                             // mengembalikan nilai res
}

func (service *authService) FindByEmail(email string) entity.User { // method find by email untuk mencari user berdasarkan email
	return service.userRepository.FindByEmail(email) // memanggil method find by email pada user repository
}

func (service *authService) IsDuplicateEmail(email string) bool { // method is duplicate email untuk mengecek email yang sudah terdaftar
	res := service.userRepository.IsDuplicateEmail(email) // memanggil method is duplicate email pada user repository
	return !(res.Error == nil)                            // mengembalikan nilai res error tidak sama dengan kosong
}

func comparePassword(hashedPwd string, plainPassword []byte) bool { // method compare password untuk membandingkan password
	byteHash := []byte(hashedPwd)                                 // membuat objek byte hash
	err := bcrypt.CompareHashAndPassword(byteHash, plainPassword) // memanggil method compare hash and password
	if err != nil {                                               // jika error tidak kosong
		return false // mengembalikan nilai false
	}
	return true // mengembalikan nilai true
}
