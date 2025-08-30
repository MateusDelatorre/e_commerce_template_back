package com.example.demo.domain.entities.user;

public class User implements IUser {
    private Long id;
    private String username;
    private String email;
    private String password;
    private String role;
    private boolean enabled;

    public User(Long id, String username, String email, String password, String role, boolean enabled) {
        this.id = id;
        this.username = username;
        this.email = email;
        this.password = password;
        this.role = role;
        this.enabled = enabled;
    }

    public User(String email, String password) {
        this.email = email;
        this.password = password;
        this.role = "customer";
    }

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
    public String getRole() {
        return role;
    }

    public boolean passwordIsValid() {
        return password != null && password.length() >= 6;
    }

    public boolean emailIsValid() {
        return email != null && email.contains("@");
    }

    @Override
    public String getPassword() {
        return this.password;
    }

    @Override
    public boolean getEnabled() {
        return this.enabled;
    }
}
