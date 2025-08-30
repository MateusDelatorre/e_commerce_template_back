package com.example.demo.infrastructure.user.dto;

import com.example.demo.domain.usecase.user.dto.IUserRegistrationData;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
public class UserRegistrationData implements IUserRegistrationData {
    private String username;
    private String email;
    private String password;
    private String role;
}
