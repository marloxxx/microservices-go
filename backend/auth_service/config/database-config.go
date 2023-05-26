package config

import (
	"fmt"
	"os"

	"github.com/joho/godotenv"
	"github.com/marloxxx/microservices-go/backend/auth_service/entity"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

// SetupDatabaseConnection is creating a new connection to our database
func SetupDatabaseConnection() *gorm.DB {
	errEnv := godotenv.Load() // load .env file
	if errEnv != nil {        // jika error
		panic("Failed to load env file") // menampilkan pesan error
	}

	dbUser := os.Getenv("DB_USER") // ambil value dari DB_USER
	dbPass := os.Getenv("DB_PASS") // ambil value dari DB_PASS
	dbHost := os.Getenv("DB_HOST") // ambil value dari DB_HOST
	dbName := os.Getenv("DB_NAME") // ambil value dari DB_NAME

	// buat data source name (dsn) untuk koneksi ke database
	dsn := fmt.Sprintf("%s:%s@tcp(%s:3306)/%s?charset=utf8&parseTime=True&loc=Local", dbUser, dbPass, dbHost, dbName)
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{}) // buat koneksi ke database
	if err != nil {                                       // jika error
		panic("Failed to create a connection to database") // menampilkan pesan error
	}
	db.AutoMigrate(&entity.Book{}, &entity.User{}) // migrasi tabel book dan user ke database
	return db                                      // kembalikan nilai db
}

func CloseDatabaseConnection(db *gorm.DB) {
	dbSQL, err := db.DB() // ambil koneksi dari db
	if err != nil {       // jika error
		panic("Failed to close a connection to database") // menampilkan pesan error
	}
	dbSQL.Close() // tutup koneksi ke database
}
