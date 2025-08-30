package com.example.demo.domain.entities.user;

public interface IUser{
    Long getId();
    String getUsername();
    String getEmail();
    String getPassword();
    String getRole();
    boolean getEnabled();
}
