package com.example.demo.data.schema;

import org.hibernate.annotations.ColumnDefault;
import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.User;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import jakarta.validation.constraints.NotBlank;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Entity
@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
@Table(name = "users_table")
public class UserSchema implements IUser{
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank
    @Column(nullable = false, length = 40, unique = true)
    private String username;

    @NotBlank
    @Column(nullable = false, length = 40, unique = true)
    private String email;

    @NotBlank
    @Column(nullable = false)
    private String password;

    private String role;

    @ColumnDefault("false")
    private boolean enabled;

    public UserSchema(User user) {
        this.id = user.getId();
        this.username = user.getUsername();
        this.email = user.getEmail();
        this.password = user.getPassword();
        this.role = user.getRole();
        this.enabled = user.getEnabled();
    }

    public User toUser() {
        return new User(
            this.id,
            this.username,
            this.email,
            this.password,
            this.role,
            this.enabled
        );
    }

    // Getters and Setters

    @Override
    public Long getId() {
        return id;
    }

    @Override
    public String getUsername() {
        return username;
    }

    @Override
    public String getEmail() {
        return email;
    }

    @Override
    public String getPassword() {
        return password;
    }

    @Override
    public String getRole() {
        return role;
    }

    @Override
    public boolean getEnabled() {
        return this.enabled;
    }
}
