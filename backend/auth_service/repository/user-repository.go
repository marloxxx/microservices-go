package repository

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/auth_service/entity"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

// UserRepository is contract what userRepository can do to db
type UserRepository interface {
	InsertUser(user entity.User) entity.User
	UpdateUser(user entity.User) entity.User
	VerifyCredential(email string, password string) interface{}
	IsDuplicateEmail(email string) (tx *gorm.DB)
	FindByEmail(email string) entity.User
	ProfileUser(userID string) entity.User
}

// membuat interface user repository dengan method insert, update, verify credential, is duplicate email, find by email, profile user

type userConnection struct {
	connection *gorm.DB
}

// membuat struct user connection dengan field connection

// NewUserRepository is create a new instance of UserRepository
func NewUserRepository(db *gorm.DB) UserRepository { // method dengan parameter db connection
	return &userConnection{
		connection: db,
	}
}

func (db *userConnection) InsertUser(user entity.User) entity.User {
	user.Password = hashAndSalt([]byte(user.Password)) // memanggil method hash and salt
	db.connection.Save(&user)                          // memanggil method save pada db connection
	return user                                        // mengembalikan nilai user
}

func (db *userConnection) UpdateUser(user entity.User) entity.User { // method update untuk mengubah data user
	if user.Password != "" { // jika password tidak kosong
		user.Password = hashAndSalt([]byte(user.Password)) // memanggil method hash and salt
	} else { // jika password kosong
		var tempUser entity.User               // membuat objek temp user
		db.connection.Find(&tempUser, user.ID) // memanggil method find pada db connection
		user.Password = tempUser.Password      // mengisi password dengan password yang lama
	}
	db.connection.Save(&user) // memanggil method save pada db connection
	return user               // mengembalikan nilai user
}

func (db *userConnection) VerifyCredential(email string, password string) interface{} { // method verify credential untuk memverifikasi email dan password
	var user entity.User                                       // membuat objek user
	res := db.connection.Where("email = ?", email).Take(&user) // memanggil method take pada db connection
	if res.Error == nil {                                      // jika res error nil
		return user // mengembalikan nilai user
	}
	return nil // mengembalikan nilai kosong
}

func (db *userConnection) IsDuplicateEmail(email string) (tx *gorm.DB) { // method is duplicate email untuk mengecek email yang sudah terdaftar
	var user entity.User                                       // membuat objek user
	return db.connection.Where("email = ?", email).Take(&user) // memanggil method take pada db connection
}

func (db *userConnection) FindByEmail(email string) entity.User { // method find by email untuk mencari user berdasarkan email
	var user entity.User                                // membuat objek user
	db.connection.Where("email = ?", email).Take(&user) // memanggil method take pada db connection
	return user                                         // mengembalikan nilai user
}

func (db *userConnection) ProfileUser(userID string) entity.User { // method profile user untuk menampilkan data user
	var user entity.User                                                     // membuat objek user
	db.connection.Preload("Books").Preload("Books.User").Find(&user, userID) // memanggil method find pada db connection
	return user                                                              // mengembalikan nilai user
}

func hashAndSalt(pwd []byte) string { // method hash and salt untuk mengenkripsi password
	hash, err := bcrypt.GenerateFromPassword(pwd, bcrypt.MinCost) // memanggil method generate from password pada bcrypt
	if err != nil {                                               // jika terjadi error
		log.Print(err)                     // menampilkan error
		panic("Failed to hash a password") // menampilkan pesan error
	}
	return string(hash) // mengembalikan nilai hash
}
