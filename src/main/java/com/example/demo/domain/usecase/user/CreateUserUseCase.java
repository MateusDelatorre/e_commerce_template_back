package com.example.demo.domain.usecase.user;

import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.user.dto.IUserRegistrationData;

public class CreateUserUseCase {
    private final IUserRepository userRepository;

    public CreateUserUseCase(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public User execute(IUserRegistrationData data) {
        System.out.println(data.getUsername());
        User user = new User(
            null,
            data.getUsername(),
            data.getEmail(),
            data.getPassword(),
            data.getRole(),
            false
        );
        return userRepository.save(user);
    }
}
