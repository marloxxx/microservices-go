package entity

type Book struct {
	ID          uint64 `gorm:"primary_key:auto_increment"`                                                  // set primary key and auto increment untuk kolom id
	Title       string `gorm:"type:varchar(255)" json:"title"`                                              // set type varchar(255) untuk kolom title
	Description string `gorm:"type:text" json:"description"`                                                // set type text untuk kolom description
	UserID      uint64 `gorm:"not null" json:"-"`                                                           // set not null untuk kolom user id
	User        User   `gorm:"foreignkey:UserID;constraint:onUpdate:CASCADE, onDelete:CASCADE" json:"user"` // set foreign key dan constraint untuk kolom user id
}
