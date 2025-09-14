package com.example.demo.domain.usecase.user;

import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.user.dto.IUserRegistrationData;

public class DeleteUserUseCase {
    private final IUserRepository userRepository;

    public DeleteUserUseCase(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public void execute(Long id) {
        userRepository.delete(id);
    }
}
